<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRequestController extends Controller
{
    public function index()
    {
        // 1. งานเข้าใหม่ (รอแอดมินโทรคอนเฟิร์ม)
        $pendingJobs = ServiceRequest::with(['customer', 'service'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. งานที่รอชำระเงิน (แจ้งลูกค้าให้โอนแล้ว)
        $waitingPaymentJobs = ServiceRequest::with(['customer', 'service'])
            ->where('status', 'awaiting_payment')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. งานที่ชำระเงินแล้ว (รอส่งให้ช่าง)
        $paidJobs = ServiceRequest::with(['customer', 'service'])
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 4. งานที่ส่งช่างแล้ว/เสร็จแล้ว (ประวัติงาน)
        $historyJobs = ServiceRequest::with(['customer', 'service', 'tech'])
            ->whereIn('status', ['approved', 'in_progress', 'completed', 'paid_confirmed'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // ==========================================
        // ✨ สถิติสำหรับ Dashboard
        // ==========================================
        $totalRevenue = ServiceRequest::where('status', 'completed')->sum('total_price');
        $completedCount = ServiceRequest::where('status', 'completed')->count();
        $activeCount = ServiceRequest::whereNotIn('status', ['completed'])->count();
        $techCount = User::where('role', 'tech')->count();

        return view('admin.requests_index', compact(
            'pendingJobs',
            'waitingPaymentJobs',
            'paidJobs',
            'historyJobs',
            'totalRevenue',
            'completedCount',
            'activeCount',
            'techCount'
        ));
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
        $service_request = ServiceRequest::findOrFail($id);
        $service_request->update([
            'tech_id' => $request->tech_id,
            'status' => 'approved',
        ]);

        // ✨ อัปเดตสถานะช่างให้กลายเป็น "ไม่ว่าง" ทันทีที่รับงาน
        User::where('id', $request->tech_id)->update(['is_available' => false]);

        return redirect()->back()->with('success', 'มอบหมายงานให้ช่างเรียบร้อยแล้ว และปรับสถานะช่างเป็นไม่ว่าง');
    }

    public function show($id)
    {
        $job = ServiceRequest::with(['customer', 'service', 'spareParts.sparePart', 'tech'])->findOrFail($id);
        return view('admin.request_show', compact('job'));
    }

    public function confirmPayment($id)
    {
        $job = ServiceRequest::findOrFail($id);
        $job->update(['status' => 'paid']);
        return redirect()->route('admin.requests')->with('success', 'ยืนยันการชำระเงินเรียบร้อย!');
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

    public function printReceipt($id)
    {
        $job = ServiceRequest::with(['customer', 'service', 'spareParts.sparePart', 'tech'])->findOrFail($id);
        return view('admin.receipt', compact('job'));
    }
}