<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // --- แยกหน้าสำหรับช่าง ---
    public function indexTech()
    {
        $techs = User::where('role', 'tech')->get();
        // ส่งไปที่ไฟล์ admin/tech_index.blade.php
        return view('admin.tech_index', compact('techs'));
    }

    // --- แยกหน้าสำหรับลูกค้า ---
    public function indexCustomer()
    {
        $customers = User::where('role', 'customer')->get();
        // ส่งไปที่ไฟล์ admin/customer_index.blade.php
        return view('admin.customer_index', compact('customers'));
    }

    // แก้ไขข้อมูล (ใช้ร่วมกันได้)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }
    
    
    // ลบ User (ใช้ร่วมกันได้)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'ลบรายชื่อออกจากระบบแล้ว');
    }

    // --- เพิ่มช่างคนใหม่ ---
    public function storeTech(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:users,phone',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'tech',
            'status' => 'available'
        ]);

        return back()->with('success', 'ลงทะเบียนช่างคนใหม่เรียบร้อย');
    }
}