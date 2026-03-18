<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia; // อย่าลืม Import ตัวนี้

class ServiceRequestController extends Controller
{
    public function create()
    {
        $services = Service::all();
        // ✨ เปลี่ยนจาก view() เป็น Inertia เพื่อให้เปิดหน้า CreateRequest.jsx
        return Inertia::render('User/CreateRequest', [
            'services' => $services
        ]);
    }

    public function store(Request $request)
    {
        // 1. หาหรือสร้าง User (ลูกค้า)
        $customer = User::firstOrCreate(
            ['phone' => $request->phone], // ค้นหาจากเบอร์โทร
            [
                'name' => $request->name,
                'email' => $request->phone . '@smcooling.com',
                'address_detail' => $request->address_detail,
                'password' => Hash::make($request->phone),
                'role' => 'customer',
            ]
        );

        // 2. จัดการสลิป (ถ้าลูกค้าโอนเงินมาเลยตอนแจ้ง)
        $fileName = null;
        $status = 'pending';
        if ($request->hasFile('slip_image')) {
            $fileName = 'slip_init_' . time() . '.' . $request->slip_image->extension();
            $request->slip_image->move(public_path('slips'), $fileName);
            $status = 'paid';
        }

        // 3. บันทึกงานแจ้งซ่อม
        $newRequest = ServiceRequest::create([
            'customer_id' => $customer->id,
            'service_id' => $request->service_id,
            'problem_details' => $request->problem_details,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => $status,
            'slip_filename' => $fileName,
        ]);

        // ✨ เปลี่ยนไปหน้าติดตามสถานะงานนั้นๆ ทันที เพื่อให้ลูกค้าเห็นรหัสงาน
        return redirect()->route('customer.requests.show', $newRequest->id)
            ->with('success', 'ส่งคำขอแจ้งซ่อมเรียบร้อยแล้ว!');
    }
}