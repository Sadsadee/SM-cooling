<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    // --- แยกหน้าสำหรับช่าง ---
    public function indexTech()
    {
        // ดึงเฉพาะ User ที่มี Role เป็น tech
        $techs = User::where('role', 'tech')->get();

        // ❌ ของเดิม: return view('admin.tech_index', compact('techs'));
        // ✅ ของใหม่:
        return Inertia::render('Admin/Techs', [
            'techs' => $techs
        ]);
    }

    // --- แยกหน้าสำหรับลูกค้า ---
    public function indexCustomer()
    {
        // ✨ วิธีที่ปลอดภัยที่สุด: ดึงรายชื่อ User ทุกคน "ที่ไม่ได้เป็น admin และไม่ได้เป็น tech"
        // วิธีนี้ต่อให้ลูกค้ามี role เป็น null หรือ 'customer' ก็จะถูกดึงมาโชว์ทั้งหมดครับ
        $users = User::whereNotIn('role', ['admin', 'tech'])
            ->orWhereNull('role') // เผื่อกรณีลูกค้าสมัครเข้ามาแล้ว role เป็นค่าว่าง
            ->get();

        return \Inertia\Inertia::render('Admin/Users', [
            'users' => $users
        ]);
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
    // ฟังก์ชันลบผู้ใช้งาน (ใช้ได้ทั้งช่างและลูกค้า)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // 🌟 แก้บรรทัด return เป็นแบบนี้ครับ เพิ่มเลข 303 เข้าไป
        return redirect()->back(303);
    }

    // --- เพิ่มช่างคนใหม่ ---
    public function storeTech(Request $request)
    {
        // 1. ตรวจสอบข้อมูลก่อนเซฟ
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. สร้าง User พร้อมยัดสิทธิ์ช่าง (ไม่ต้องใส่ bcrypt เพราะ User.php ทำ hashed ให้แล้ว)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // 👈 ส่งไปตรงๆ ได้เลย
            'role' => 'tech', // 👈 หัวใจสำคัญอยู่ตรงนี้! บังคับให้เป็น tech
        ]);

        // 3. รีเฟรชหน้าเพื่อให้ React ดึงข้อมูลใหม่
        return back();
    }
}