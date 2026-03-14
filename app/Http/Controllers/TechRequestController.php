<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TechRequestController extends Controller
{
    // แสดงรายการงานที่ช่างคนนี้ได้รับมอบหมาย
    public function index()
    {
        // งานที่ต้องทำ (วันนี้)
        $jobs = ServiceRequest::with(['customer', 'service'])
            ->where('tech_id', Auth::id())
            ->whereIn('status', ['approved', 'in_progress'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        // งานที่ทำเสร็จแล้ว (ประวัติ)
        $history = ServiceRequest::with(['customer', 'service'])
            ->where('tech_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('tech.dashboard', compact('jobs', 'history'));
    }

    public function show($id)
    {
        $job = ServiceRequest::with(['customer', 'service'])->findOrFail($id);

        // ตรงนี้เราจะสร้างไฟล์ View ใหม่ชื่อ job_detail ในโฟลเดอร์ tech
        return view('tech.job_detail', compact('job'));
    }

    public function addPart(Request $request, $id)
    {
        // 1. รับค่าและหาข้อมูลอะไหล่
        $part = \App\Models\SparePart::findOrFail($request->spare_part_id);

        // 2. บันทึกข้อมูลลงตารางเชื่อม (ใช้ชื่อคอลัมน์ตาม Migration ของคุณ)
        \App\Models\RequestSparePart::create([
            'request_id' => $id,            // ตาม Migration คุณใช้ชื่อนี้
            'part_id' => $part->id,      // ตาม Migration คุณใช้ชื่อนี้
            'quantity' => $request->quantity,
            'total_price' => $part->price * $request->quantity // บันทึกราคารวมของแถวนี้
        ]);

        // 3. ตัดสต็อกอะไหล่ออกจากคลัง
        $part->decrement('stock', $request->quantity);

        return redirect()->back()->with('success', 'เพิ่มอะไหล่เรียบร้อยแล้ว');
    }

    // ฟังก์ชันช่างกดแจ้งจบงาน
    public function completeJob(Request $request, $id)
    {
        // 1. ดึงข้อมูลงานพร้อมรายการอะไหล่และค่าบริการ
        $job = \App\Models\ServiceRequest::with(['service', 'spareParts'])->findOrFail($id);

        // 2. คำนวณราคาสรุปสุดท้าย (ค่าบริการ + ผลรวมราคาทุกแถวในอะไหล่)
        $totalPartsPrice = $job->spareParts->sum('total_price');
        $finalPrice = $job->service->base_price + $totalPartsPrice;

        // 3. อัปเดตสถานะงานเป็นเสร็จสิ้น และบันทึกยอดเงินรวมที่ต้องเก็บจริง
        $job->update([
            'status' => 'completed',
            'total_price' => $finalPrice
        ]);

        // ✨ 4. อัปเดตสถานะช่างให้กลับมา "ว่าง" พร้อมรับงานใหม่
        auth()->user()->update(['is_available' => true]);

        // 5. กลับหน้าหลักพร้อมข้อความแจ้งเตือน
        return redirect()->route('tech.dashboard')->with('success', 'ปิดงานเรียบร้อยแล้ว! สถานะของคุณคือ: ว่าง (พร้อมรับงานใหม่)');
    }
}