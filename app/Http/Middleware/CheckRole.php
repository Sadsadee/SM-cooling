<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, $next, $role)
    {
        // ถ้ายังไม่ Login หรือ Role ไม่ตรงตามที่กำหนด ให้ดีดไปหน้า Dashboard หลัก
        if (!auth()->check() || auth()->user()->role !== $role) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
