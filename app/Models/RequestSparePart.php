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

public function sparePart()
{
    // ถ้าในตารางตั้งชื่อว่า part_id ให้ใส่แบบนี้ครับ
    return $this->belongsTo(SparePart::class, 'part_id');
}
}