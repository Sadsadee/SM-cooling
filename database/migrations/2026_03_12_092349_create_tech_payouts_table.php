<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tech_payouts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tech_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('request_id')->constrained('service_requests')->onDelete('cascade');
    $table->decimal('amount', 10, 2); // ยอดเงินส่วนแบ่ง
    $table->date('payment_date')->nullable(); // วันที่โอนให้ช่าง
    $table->enum('status', ['pending', 'paid'])->default('pending'); // สถานะ (รอจ่าย, จ่ายแล้ว)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_payouts');
    }
};
