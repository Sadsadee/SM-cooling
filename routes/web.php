<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\TechRequestController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSparePartController;

Route::get('/', function () {
    return view('welcome');
});

// ระบบจัดการ Dashboard ตามสิทธิ์ (เช็คสิทธิ์หลัง Login)
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.requests');
    } elseif ($role === 'tech') {
        return redirect()->route('tech.dashboard');
    } else {
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ====================================================================
// กลุ่ม 1: สำหรับลูกค้าทั่วไป (ไม่ต้อง Login)
// ====================================================================
Route::get('/request-service', [ServiceRequestController::class, 'create'])->name('request.create');
Route::post('/request-service', [ServiceRequestController::class, 'store'])->name('request.store');
Route::get('/check-phone', [ServiceRequestController::class, 'checkPhone'])->name('check.phone');

Route::get('/track-status', [CustomerController::class, 'trackIndex'])->name('track.index');
Route::get('/track-status/search', [CustomerController::class, 'trackSearch'])->name('track.search');

Route::get('/payment/{id}', [CustomerController::class, 'paymentForm'])->name('payment.form');
Route::post('/payment/{id}', [CustomerController::class, 'uploadSlip'])->name('payment.upload');


// ====================================================================
// กลุ่ม 2: สำหรับผู้ใช้งานที่ผ่านการยืนยันตัวตน (Login เท่านั้น)
// ====================================================================
Route::middleware('auth')->group(function () {

    // --- โปรไฟล์ส่วนตัว ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ส่วนของแอดมิน (Admin) ---
    Route::prefix('admin')->group(function () {

        // 1. จัดการคำร้อง (Service Requests)
        Route::get('/requests', [AdminRequestController::class, 'index'])->name('admin.requests');
        Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('admin.requests.show');
        Route::get('/requests/{id}/receipt', [AdminRequestController::class, 'printReceipt'])->name('admin.requests.receipt');
        Route::post('/requests/{id}/request-payment', [AdminRequestController::class, 'requestPayment'])->name('admin.request_payment');
        Route::post('/requests/{id}/assign-tech', [AdminRequestController::class, 'assignTech'])->name('admin.requests.assign');
        Route::post('/requests/{id}/confirm-payment', [AdminRequestController::class, 'confirmPayment'])->name('admin.requests.confirm_payment');
        Route::post('/requests/{id}/approve', [AdminRequestController::class, 'approve'])->name('admin.requests.approve');
        Route::post('/admin/techs', [AdminUserController::class, 'storeTech'])->name('admin.techs.store');

        // 2. จัดการผู้ใช้งาน (แยกหน้าตามที่คุณต้องการ)
        Route::get('/techs', [AdminUserController::class, 'indexTech'])->name('admin.techs.index');      // หน้าจัดการช่าง
        Route::get('/users', [AdminUserController::class, 'indexCustomer'])->name('admin.users.index'); // หน้าจัดการลูกค้า
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/tech/{id}/update-password', [AdminRequestController::class, 'updateTechPassword'])->name('admin.tech.update_password');

        // 3. ระบบคลังอะไหล่และบริการ
        Route::get('/inventory', [AdminSparePartController::class, 'index'])->name('admin.inventory.index');
        Route::post('/inventory/part', [AdminSparePartController::class, 'storePart'])->name('admin.inventory.storePart');
        Route::put('/inventory/part/{id}', [AdminSparePartController::class, 'updatePart'])->name('admin.inventory.updatePart');
        Route::post('/inventory/service', [AdminSparePartController::class, 'storeService'])->name('admin.inventory.storeService');
        Route::put('/inventory/service/{id}', [AdminSparePartController::class, 'updateService'])->name('admin.inventory.updateService');
    });

    // --- ส่วนของช่าง (Tech) ---
    Route::prefix('tech')->group(function () {
        Route::get('/dashboard', [TechRequestController::class, 'index'])->name('tech.dashboard');
        Route::get('/requests', [TechRequestController::class, 'index'])->name('tech.requests');
        Route::get('/requests/{id}', [TechRequestController::class, 'show'])->name('tech.requests.show');
        Route::post('/requests/{id}/add-part', [TechRequestController::class, 'addPart'])->name('tech.add_part');
        Route::post('/requests/{id}/complete', [TechRequestController::class, 'completeJob'])->name('tech.complete_job');
    });

    // --- ส่วนของลูกค้า (เมื่อ Login เข้ามาดูประวัติ) ---
    Route::get('/my-requests', [CustomerController::class, 'myRequests'])->name('customer.requests');
});

require __DIR__ . '/auth.php';