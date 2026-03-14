<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparePart;
use App\Models\Service;

class AdminSparePartController extends Controller
{
    public function index()
    {
        $parts = SparePart::all();
        $services = Service::all();
        return view('admin.inventory_index', compact('parts', 'services'));
    }

    // จัดการอะไหล่ (เพิ่ม/อัปเดตสต็อก)
    public function updatePart(Request $request, $id)
    {
        $part = SparePart::findOrFail($id);
        $part->update($request->only(['part_name', 'price', 'stock']));
        return back()->with('success', 'อัปเดตข้อมูลอะไหล่เรียบร้อย');
    }

    // จัดการบริการ (แก้ไขราคาค่าบริการหลัก)
    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->only(['service_name', 'base_price']));
        return back()->with('success', 'อัปเดตข้อมูลบริการเรียบร้อย');
    }
    // เพิ่มอะไหล่ใหม่
    public function storePart(Request $request)
    {
        $request->validate([
            'part_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        SparePart::create($request->all());
        return back()->with('success', 'เพิ่มอะไหล่ใหม่เรียบร้อย');
    }

    // เพิ่มบริการใหม่
    public function storeService(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'base_price' => 'required|numeric',
        ]);
        Service::create($request->all());
        return back()->with('success', 'เพิ่มประเภทบริการใหม่เรียบร้อย');
    }
}