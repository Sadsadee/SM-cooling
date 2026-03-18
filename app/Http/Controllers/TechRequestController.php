<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\SparePart;           // ✨ เพิ่มไว้ด้านบน
use App\Models\RequestSparePart;    // ✨ เพิ่มไว้ด้านบน
use App\Models\User;                // ✨ เพิ่มไว้ด้านบน
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TechRequestController extends Controller
{
    // แสดงรายการงานที่ช่างคนนี้ได้รับมอบหมาย
    // แสดงรายการงานที่ช่างคนนี้ได้รับมอบหมาย
    public function index()
    {
        $userId = Auth::id();

        // 1. คิวงานปัจจุบัน (in_progress)
        $pendingJobs = ServiceRequest::with(['customer', 'service'])
            ->where('tech_id', $userId)
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. ประวัติการทำงาน (completed) - ดึงมาโชว์แค่ 10 งานล่าสุดจะได้ไม่หนักเครื่อง
        $completedJobs = ServiceRequest::with(['customer', 'service'])
            ->where('tech_id', $userId)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Tech/Dashboard', [
            'pendingJobs' => $pendingJobs,
            'completedJobs' => $completedJobs
        ]);
    }

    public function show($id)
    {
        // ✨ ใช้ spare_parts (snake_case) ตามที่ตั้งใน Model
        $job = ServiceRequest::with(['customer', 'service', 'spare_parts.spare_part'])
            ->findOrFail($id);

        // ดึงอะไหล่ที่มีในคลังไปให้ช่างเลือก
        $inventory = SparePart::where('stock', '>', 0)->get();

        return Inertia::render('Tech/Show', [
            'job' => $job,
            'inventory' => $inventory
        ]);
    }

    public function addPart(Request $request, $id)
    {
        // 1. รับค่าและหาข้อมูลอะไหล่
        $part = SparePart::findOrFail($request->spare_part_id);

        // 🚨 ตรวจสอบสต็อกก่อนตัด (กันเหนียว)
        if ($part->stock < $request->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'สินค้าในคลังไม่พอ']);
        }

        // 2. บันทึกข้อมูลลงตารางเชื่อม
        RequestSparePart::create([
            'request_id' => $id,            // ตรวจสอบชื่อคอลัมน์ใน DB ให้ตรงกับใน Model
            'part_id' => $part->id,
            'quantity' => $request->quantity,
            'price_at_time' => $part->price, // ✨ แนะนำให้เก็บราคา ณ ตอนนั้นไว้ด้วย
            'total_price' => $part->price * $request->quantity
        ]);

        // 3. ตัดสต็อกอะไหล่ออกจากคลัง
        $part->decrement('stock', $request->quantity);

        return redirect()->back()->with('success', 'เพิ่มอะไหล่เรียบร้อยแล้ว');
    }

    // ฟังก์ชันช่างกดแจ้งจบงาน
    public function completeJob(Request $request, $id)
    {
        // 1. ดึงข้อมูลงาน (เปลี่ยนจาก spareParts เป็น spare_parts ให้ตรงกัน)
        $job = ServiceRequest::with(['service', 'spare_parts'])->findOrFail($id);

        // 2. คำนวณราคาสรุปสุดท้าย
        $totalPartsPrice = $job->spare_parts->sum('total_price');
        $finalPrice = $job->service->base_price + $totalPartsPrice;

        // 3. อัปเดตสถานะงาน
        $job->update([
            'status' => 'completed',
            'total_price' => $finalPrice
        ]);

        // 4. อัปเดตสถานะช่างให้กลับมา "ว่าง"
        // ใช้ Auth::user() โดยตรงได้เลยครับ
        Auth::user()->update(['is_available' => true]);

        return redirect()->route('tech.dashboard')->with('success', 'ปิดงานเรียบร้อยแล้ว! ขอบคุณที่ตั้งใจทำงานครับ');
    }
}