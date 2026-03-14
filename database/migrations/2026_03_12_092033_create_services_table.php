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
        Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->enum('service_type', ['cleaning', 'repair'])->comment('ประเภท: ล้าง หรือ ซ่อม');
    $table->string('service_name'); // ชื่อบริการ
    $table->decimal('base_price', 8, 2); // ราคาเริ่มต้น
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
