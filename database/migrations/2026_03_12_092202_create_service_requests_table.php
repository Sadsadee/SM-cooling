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
        Schema::create('service_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained('users')->onDelete('cascade'); // รหัสลูกค้า
    $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // รหัสบริการ
    $table->text('problem_details')->nullable(); // อาการเสีย
    $table->date('appointment_date'); // วันที่นัดหมาย
    $table->time('appointment_time'); // เวลานัดหมาย
    $table->foreignId('tech_id')->nullable()->constrained('users')->onDelete('set null'); // รหัสช่างที่รับผิดชอบ
    $table->enum('status', ['pending', 'awaiting_payment', 'paid', 'approved', 'in_progress', 'completed', 'cancelled'])->default('pending');
    $table->decimal('total_price', 10, 2)->nullable(); // ราคารวม
    $table->string('slip_filename')->nullable(); // ชื่อไฟล์สลิปโอนเงิน
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
