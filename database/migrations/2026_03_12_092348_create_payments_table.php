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
        Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('service_requests')->onDelete('cascade');
    $table->decimal('amount', 10, 2); // ยอดโอน
    $table->string('payment_method')->default('transfer')->comment('ช่องทางชำระเงิน');
    $table->string('slip_image')->nullable(); // ไฟล์สลิปโอนเงิน
    $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // สถานะการจ่าย
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
