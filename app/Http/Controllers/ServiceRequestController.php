<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Hash;

class ServiceRequestController extends Controller
{
    public function create()
    {
        $services = Service::all();
        return view('request_service', compact('services'));
    }

    public function checkPhone(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            return response()->json(['found' => true, 'data' => $user]);
        } else {
            return response()->json(['found' => false]);
        }
    }

    public function store(Request $request)
    {
        // ลบ dd ออกก่อนนะครับ

        // 1. หาหรือสร้าง User (ลูกค้า)
        $customer = \App\Models\User::firstOrCreate(
            ['phone' => $request->phone], // ค้นหาจากเบอร์โทร
            [
                'name' => $request->name,
                'email' => $request->phone . '@smcooling.com',
                'address_detail' => $request->address_detail,
                'subdistrict' => $request->subdistrict,
                'district' => $request->district,
                'province' => $request->province,
                'password' => \Illuminate\Support\Facades\Hash::make($request->phone),
                'role' => 'customer',
            ]
        );

        // 2. จัดการสลิป (ถ้ามี)
        $fileName = null;
        $status = 'pending';
        if ($request->hasFile('slip_image')) {
            $fileName = 'slip_init_' . time() . '.' . $request->slip_image->extension();
            $request->slip_image->move(public_path('slips'), $fileName);
            $status = 'paid';
        }

        // 3. บันทึกงาน (ใช้คำสั่งนี้เพื่อดู Error ถ้าบันทึกไม่เข้า)
        $newRequest = new \App\Models\ServiceRequest();
        $newRequest->customer_id = $customer->id;
        $newRequest->service_id = $request->service_id;
        $newRequest->problem_details = $request->problem_details;
        $newRequest->appointment_date = $request->appointment_date;
        $newRequest->appointment_time = $request->appointment_time;
        $newRequest->status = $status;
        $newRequest->slip_filename = $fileName;
        $newRequest->save();

        return redirect('/') // เปลี่ยนจาก ->route('/') เป็น ('/') เฉยๆ
            ->with('success', 'ส่งคำขอแจ้งซ่อมเรียบร้อยแล้ว! แอดมินจะรีบตรวจสอบและติดต่อกลับโดยเร็วที่สุด');
    }
}