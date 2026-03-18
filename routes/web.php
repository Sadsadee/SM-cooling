<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\TechRequestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSparePartController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TechnicianController;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (ลูกค้าทั่วไป - ไม่ต้อง Login)
|--------------------------------------------------------------------------
*/

// หน้า Landing Page เดิมของลูกพี่
Route::get('/', [CustomerController::class, 'index'])->name('home');

// 🌐 ระบบแจ้งซ่อม (ใครก็แจ้งได้)
Route::controller(ServiceRequestController::class)->group(function () {
    Route::get('/request-service', 'create')->name('request.create');
    Route::post('/request-service', 'store')->name('request.store');
    Route::get('/check-phone', 'checkPhone')->name('check.phone');
});

// 🔍 ระบบติดตามสถานะ และ อัปโหลดสลิป (ไม่ต้อง Login ใช้ค้นหาเอา)
Route::controller(CustomerController::class)->group(function () {
    // หน้า Dashboard/Landing ของลูกค้า (ถ้ามี)
    Route::get('/services', 'index')->name('customer.dashboard');

    // ระบบค้นหาและติดตามด้วยเบอร์โทร
    Route::get('/track-status', 'trackIndex')->name('track.index');
    Route::get('/track-status/search', 'trackSearch')->name('track.search');

    // หน้าดูรายละเอียดงานรายชิ้น และอัปโหลดสลิป (Public)
    Route::get('/customer/requests/{id}', 'show')->name('customer.requests.show');
    Route::post('/customer/requests/{id}/upload-slip', 'uploadSlip')->name('customer.upload_slip');
});

/*
|--------------------------------------------------------------------------
| 2. Authentication (ระบบ Login สำหรับพนักงาน Admin/Tech เท่านั้น)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| 3. Protected Routes (เฉพาะ Admin และ Tech ที่ต้อง Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 🎯 ตัวกระจายรถ (Dashboard Redirector)
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.requests'),
            'tech' => redirect()->route('tech.dashboard'),
            // ถ้าลูกค้าเผลอ Login เข้ามา (ซึ่งจริงๆ ไม่ต้อง) ให้เด้งกลับหน้าแรก
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // --- Profile Management ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /* --- ✨ ส่วนของแอดมิน (Admin Only) --- */
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/requests', [AdminRequestController::class, 'index'])->name('admin.requests');
        Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('admin.requests.show');

        Route::controller(AdminRequestController::class)->group(function () {
            Route::post('/requests/{id}/request-payment', 'requestPayment')->name('admin.request_payment');
            Route::post('/confirm-payment/{id}', 'confirmPayment')->name('admin.confirm_payment');
            Route::post('/requests/{id}/assign-tech', 'assignTech')->name('admin.requests.assign');
            Route::get('/requests/{id}/receipt', 'receipt')->name('admin.requests.receipt');
        });

        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/techs', 'indexTech')->name('admin.techs.index');
            Route::post('/techs', 'storeTech')->name('admin.techs.store');
            Route::get('/users', 'indexCustomer')->name('admin.users.index');
            Route::put('/users/{id}', 'update')->name('admin.users.update');
            Route::delete('/users/{id}', 'destroy')->name('admin.users.destroy');
        });

        Route::controller(AdminSparePartController::class)->group(function () {
            Route::get('/inventory', 'index')->name('admin.inventory.index');
            Route::post('/inventory/part', 'storePart')->name('admin.inventory.storePart');
            Route::put('/inventory/part/{id}', 'updatePart')->name('admin.inventory.updatePart');
            Route::post('/inventory/service', 'storeService')->name('admin.inventory.storeService');
            Route::put('/inventory/service/{id}', 'updateService')->name('admin.inventory.updateService');
        });
    });

    /* --- 👨‍🔧 ส่วนของช่าง (Tech Only) --- */
    Route::middleware(['role:tech'])->prefix('tech')->group(function () {
        Route::controller(TechRequestController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('tech.dashboard');
            Route::get('/requests/{id}', 'show')->name('tech.requests.show');
            Route::post('/requests/{id}/add-part', 'addPart')->name('tech.add_part');
            Route::post('/requests/{id}/complete', 'completeJob')->name('tech.complete_job');
            // หน้าหลักของช่าง (ดูงานที่ได้รับมอบหมาย)
            Route::get('/dashboard', [TechnicianController::class, 'index'])->name('tech.dashboard');
            // อัปเดตสถานะงาน (เช่น กดเริ่มงาน หรือ กดปิดงาน)
            Route::post('/requests/{id}/status', [TechnicianController::class, 'updateStatus'])->name('tech.status.update');
        });
    });
});