<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRequestController extends Controller
{
    public function index()
    {
        // 1. งานเข้าใหม่เหมือนเดิม
        $pendingJobs = \App\Models\ServiceRequest::with(['customer', 'service'])
            ->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        // ✅ 2. ช่องกลาง: ดึงทั้ง 'รอโอน' และ 'จ่ายแล้ว (รอตรวจสลิป)'
        $waitingPaymentJobs = \App\Models\ServiceRequest::with(['customer', 'service'])
            ->whereIn('status', ['awaiting_payment', 'paid']) // ดึงมาทั้งคู่
            ->orderBy('updated_at', 'desc')->get();

        // ✅ 3. ช่องขวา: ดึงเฉพาะงานที่ 'แอดมินกดยืนยันสลิปแล้ว' (พร้อมส่งช่าง)
        $paidJobs = \App\Models\ServiceRequest::with(['customer', 'service'])
            ->where('status', 'approved') // เปลี่ยนจาก paid เป็น approved
            ->orderBy('updated_at', 'desc')->get();

        // 4. ประวัติงาน (เพิ่มสถานะ approved เข้าไปด้วย)
        $historyJobs = \App\Models\ServiceRequest::with(['customer', 'service', 'tech'])
            ->whereIn('status', ['approved', 'in_progress', 'completed', 'paid_confirmed'])
            ->orderBy('updated_at', 'desc')->get();

        // สถิติและอื่นๆ เหมือนเดิม...
        $totalRevenue = \App\Models\ServiceRequest::where('status', 'completed')->sum('total_price');
        $completedCount = \App\Models\ServiceRequest::where('status', 'completed')->count();
        $activeCount = \App\Models\ServiceRequest::whereNotIn('status', ['completed'])->count();
        $techCount = \App\Models\User::where('role', 'tech')->count();
        $techs = \App\Models\User::where('role', 'tech')->get();

        // 1. ดึงรายการงานที่ปิดจ๊อบแล้ว (เอามาโชว์ในกล่องรายได้ และ ปิดจ๊อบ)
        $completedJobsList = ServiceRequest::with(['customer', 'service', 'tech'])
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 2. ดึงรายการงานที่รอดำเนินการ (สำหรับ Modal)
        $activeJobsList = ServiceRequest::with(['customer', 'service', 'tech'])
            ->where('status', 'in_progress') // หรือสถานะที่ลูกพี่ใช้
            ->orderBy('updated_at', 'desc')
            ->get();

        // จากนั้นอย่าลืมส่ง $completedJobsList และ $activeJobsList แนบไปใน return Inertia ด้วยนะครับ!
        $inProgressJobs = ServiceRequest::with('customer', 'service', 'tech')->where('status', 'in_progress')->get();
        $completedJobs = ServiceRequest::with('customer', 'service', 'tech')->where('status', 'completed')->limit(10)->get();

        return Inertia::render('AdminDashboard', [
            'totalRevenue' => $totalRevenue,
            'completedCount' => $completedCount,
            'activeCount' => $activeCount,
            'techCount' => $techCount,
            'pendingJobs' => $pendingJobs,
            'waitingPaymentJobs' => $waitingPaymentJobs,
            'paidJobs' => $paidJobs,
            'techs' => $techs,
            'completedJobsList' => $completedJobsList,
            'activeJobsList' => $activeJobsList,
        ]);
    }

    public function requestPayment($id)
    {
        $job = ServiceRequest::findOrFail($id);
        $job->update(['status' => 'awaiting_payment']);
        return back()->with('success', 'แจ้งให้ลูกค้าชำระเงินแล้ว!');
    }

    // ฟังก์ชัน จ่ายงานให้ช่าง
    public function assignTech(Request $request, $id)
    {
        $job = \App\Models\ServiceRequest::findOrFail($id);

        $job->update([
            'tech_id' => $request->tech_id,
            'status' => 'in_progress' // ✨ เปลี่ยนจาก assigned เป็น in_progress ครับ
        ]);

        return back();
    }

    public function show($id)
    {
        // ดึงข้อมูลงาน พร้อมผูกความสัมพันธ์กับตารางอื่นให้ครบ
        $job = ServiceRequest::with(['customer', 'service', 'spareParts.sparePart', 'tech',])->findOrFail($id);

        // ✨ เปลี่ยนจาก return view(...) เป็น Inertia::render(...)
        return Inertia::render('Admin/RequestShow', [
            'job' => $job
        ]);
    }

    public function confirmPayment($id)
    {
        $job = \App\Models\ServiceRequest::findOrFail($id);

        // 1. อัปเดตสถานะเป็น approved
        $job->update([
            'status' => 'approved'
        ]);

        // 2. ✨ แก้ตรงนี้ครับ! สั่งให้เด้งกลับไปหน้า Dashboard (Kanban)
        // เปลี่ยนจาก return back(); เป็นโค้ดด้านล่างนี้
        return redirect()->route('admin.requests')->with('success', 'ยืนยันการชำระเงินเรียบร้อยแล้ว!');

        // 💡 หมายเหตุ: ถ้าลูกพี่ไม่ได้ตั้งชื่อ route ไว้ ให้ใช้ URL ตรงๆ แบบนี้แทนได้ครับ:
        // return redirect('/admin/requests'); 
    }

    public function updateTechPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6',
        ]);

        $tech = User::where('role', 'tech')->findOrFail($id);
        $tech->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านให้ช่าง ' . $tech->name . ' เรียบร้อยแล้ว!');
    }

    // ✨ แก้ชื่อฟังก์ชันให้ตรงกับ web.php แล้วครับ
    public function receipt($id)
    {
        $job = ServiceRequest::with(['customer', 'service', 'spareParts.sparePart', 'tech'])->findOrFail($id);
        return view('admin.receipt', compact('job'));
    }
}