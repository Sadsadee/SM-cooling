<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSparePart extends Model
{
    use HasFactory;

    protected $fillable = ['request_id', 'part_id', 'quantity', 'total_price'];

    // เชื่อมกลับไปหาข้อมูลอะไหล่เพื่อดึงชื่อมาโชว์
    // ไฟล์ app/Models/RequestSparePart.php

    // 📂 เปิดไฟล์ app/Models/RequestSparePart.php 
// แล้วเพิ่มฟังก์ชันนี้เข้าไปครับ

    public function spare_part()
    {
        return $this->belongsTo(SparePart::class, 'part_id');
    }
}