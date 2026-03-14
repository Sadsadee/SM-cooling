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
        Schema::create('request_spare_parts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('service_requests')->onDelete('cascade'); // อ้างอิงใบแจ้งซ่อม
    $table->foreignId('part_id')->constrained('spare_parts')->onDelete('cascade'); // อ้างอิงอะไหล่
    $table->integer('quantity'); // จำนวนที่ใช้
    $table->decimal('total_price', 8, 2); // ราคารวมของอะไหล่รายการนี้
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_spare_parts');
    }
};
