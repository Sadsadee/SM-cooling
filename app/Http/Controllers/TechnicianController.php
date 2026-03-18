<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Inertia\Inertia;

class TechnicianController extends Controller
{
    public function index()
    {
        // ดึงงานที่แอดมินมอบหมายให้ช่างคนนี้ (ดึงข้อมูลลูกค้าและบริการมาด้วย)
        $jobs = ServiceRequest::with(['customer', 'service'])
            ->where('tech_id', auth()->id())
            ->whereIn('status', ['approved', 'in_progress']) // โชว์เฉพาะงานที่ยังไม่เสร็จ
            ->orderBy('appointment_date', 'asc')
            ->get();

        return Inertia::render('Tech/Dashboard', [
            'jobs' => $jobs
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $job = ServiceRequest::where('tech_id', auth()->id())->findOrFail($id);
        
        // รับค่าสถานะใหม่จากปุ่มที่กด (เช่น in_progress หรือ completed)
        $job->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'อัปเดตสถานะงานเรียบร้อยแล้ว!');
    }
}