<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // อนุญาตให้บันทึกข้อมูลประเภทบริการและราคาเริ่มต้น
    protected $fillable = [
        'service_name', 
        'base_price'
    ];
}