<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // หรือจะใช้ Request ปกติก็ได้
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    // 1. หน้า Login (ดึงจาก Pages/Auth/Login.jsx)
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    // 2. ฟังก์ชันตรวจสอบการเข้าสู่ระบบ
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // ✨ เข้าได้แล้วให้ไปที่ Dashboard
            return redirect()->intended(route('admin.requests'));
        }

        // ❌ ถ้าผิดให้ส่ง Error กลับไป
        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    // 3. ฟังก์ชันออกจากระบบ (Logout)
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ❌ จากเดิม: return redirect('/'); 
        // ✅ เปลี่ยนเป็น:
        return Inertia::location('/');
    }
}