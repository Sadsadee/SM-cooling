<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\User;

class CustomerController extends Controller
{
    // หน้า 1: แบบฟอร์มกรอกเบอร์โทรเพื่อค้นหางาน
    public function trackIndex()
    {
        return view('customer.track_index');
    }

    // ฟังก์ชันค้นหางานจากเบอร์โทร
    public function trackSearch(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric'
        ], [
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.numeric' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลขเท่านั้น'
        ]);

        // ค้นหาลูกค้าจากเบอร์โทร
        $customer = User::where('phone', $request->phone)->where('role', 'customer')->first();

        if (!$customer) {
            return back()->with('error', 'ไม่พบประวัติการแจ้งซ่อมจากเบอร์โทรศัพท์นี้');
        }

        // ดึงประวัติงานของลูกค้าคนนี้ทั้งหมด เรียงจากใหม่ไปเก่า
        $jobs = ServiceRequest::with('service')
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.track_results', compact('customer', 'jobs'));
    }

    // ฟังก์ชันสำหรับลูกค้าอัปโหลดสลิป
    public function uploadSlip(Request $request, $id)
    {
        $request->validate([
            'slip_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // รับรูปขนาดไม่เกิน 5MB
        ]);

        $job = ServiceRequest::findOrFail($id);

        if ($request->hasFile('slip_image')) {
            $file = $request->file('slip_image');
            $filename = time() . '_' . $job->id . '.' . $file->getClientOriginalExtension();
            
            // ย้ายไฟล์ไปเก็บที่โฟลเดอร์ public/slips
            $file->move(public_path('slips'), $filename);

            // อัปเดตฐานข้อมูล
            $job->update([
                'slip_filename' => $filename,
                'status' => 'paid' // เปลี่ยนสถานะเป็น paid เพื่อให้ไปโผล่ในคอลัมน์ 3 ของแอดมิน
            ]);

            return redirect('/') // เด้งกลับหน้าหลัก (Landing Page)
        ->with('success', 'ส่งหลักฐานการโอนเงินเรียบร้อยแล้ว! ขอบคุณที่ใช้บริการ SM Cooling Center ครับ');
        }

        return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ');
    }
}