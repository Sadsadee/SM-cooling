<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Service;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * หน้าแรกของลูกค้า (Dashboard)
     */
    public function index()
    {
        $services = Service::all();
        // ดึงงานล่าสุดมาโชว์ (ถ้าไม่ login ก็จะได้ array ว่าง)
        $activeRequests = auth()->check()
            ? ServiceRequest::with('service')->where('customer_id', auth()->id())->get()
            : [];

        return Inertia::render('User/Dashboard', [
            'services' => $services,
            'activeRequests' => $activeRequests
        ]);
    }
    /**
     * หน้าสำหรับกรอกเบอร์โทรเพื่อค้นหาสถานะ (หน้า Index ของการติดตาม)
     */
    public function trackIndex()
    {
        // สั่ง Render ไปที่หน้า TrackIndex ในโฟลเดอร์ User
        return \Inertia\Inertia::render('User/TrackIndex');
    }

    /**
     * ฟังก์ชันค้นหางานจากเบอร์โทร (ผลลัพธ์)
     */
    public function trackSearch(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        // ค้นหาลูกค้าจากเบอร์โทร
        $customer = \App\Models\User::where('phone', $request->phone)->first();

        if (!$customer) {
            return back()->with('error', 'ไม่พบประวัติการแจ้งซ่อมจากเบอร์โทรศัพท์นี้');
        }

        // ดึงประวัติงานทั้งหมดของเบอร์นี้
        $jobs = \App\Models\ServiceRequest::with('service')
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return \Inertia\Inertia::render('User/TrackResults', [
            'customer' => $customer,
            'jobs' => $jobs
        ]);
    }

    /**
     * หน้าฟอร์มสร้างใบแจ้งซ่อม
     */
    public function create()
    {
        $services = Service::all();
        return Inertia::render('User/CreateRequest', [
            'services' => $services
        ]);
    }

    /**
     * หน้าดูรายละเอียดสถานะงาน และ อัปโหลดสลิป
     */
    public function show($id)
    {
        // ❌ ลบ ->where('customer_id', auth()->id()) ออก
        // เพราะตอนนี้ลูกค้าไม่ได้ Login มาครับ
        $job = ServiceRequest::with(['service', 'customer', 'tech', 'spare_parts.spare_part'])
            ->findOrFail($id); // หาแค่ ID งานก็พอ

        return Inertia::render('User/ShowStatus', [
            'job' => $job
        ]);
    }

    /**
     * ฟังก์ชันบันทึกการแจ้งซ่อมใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'problem_details' => 'required|string|max:500',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $requestItem = ServiceRequest::create([
            'customer_id' => auth()->id(),
            'service_id' => $request->service_id,
            'problem_details' => $request->problem_details,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'pending', // เริ่มต้นที่สถานะงานเข้าใหม่
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'ส่งข้อมูลแจ้งซ่อมเรียบร้อยแล้ว!');
    }

    /**
     * ฟังก์ชันสำหรับลูกค้าอัปโหลดสลิป
     */
    public function uploadSlip(Request $request, $id)
    {
        $request->validate([
            'slip_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ❌ ลบ ->where('customer_id', auth()->id()) ออก
        $job = ServiceRequest::findOrFail($id);

        if ($request->hasFile('slip_image')) {
            $file = $request->file('slip_image');
            $fileName = time() . '_' . $job->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('slips'), $fileName);

            $job->update([
                'slip_filename' => $fileName,
                'status' => 'paid',
            ]);
        }

        return back()->with('success', 'แจ้งโอนเงินเรียบร้อยแล้ว');
    }
}