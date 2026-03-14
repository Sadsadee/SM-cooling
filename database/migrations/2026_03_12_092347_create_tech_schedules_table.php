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
        Schema::create('tech_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tech_id')->constrained('users')->onDelete('cascade'); // อ้างอิงช่าง
    $table->date('available_date'); // วันที่ว่าง
    $table->enum('time_slot', ['morning', 'afternoon', 'full_day'])->comment('ช่วงเวลา: เช้า, บ่าย, เต็มวัน');
    $table->boolean('is_booked')->default(false)->comment('โดนจองคิวไปหรือยัง (0=ยัง, 1=จองแล้ว)');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_schedules');
    }
};
