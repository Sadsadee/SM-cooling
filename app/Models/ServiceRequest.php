<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'problem_details',
        'appointment_date',
        'appointment_time',
        'tech_id',
        'status',
        'total_price',
        'slip_filename'
    ];

    // เชื่อมกับตาราง User (เพื่อดึงชื่อลูกค้า)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // เชื่อมกับตาราง Service (เพื่อดึงชื่อบริการ)
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // เชื่อมกับตาราง User (เพื่อดึงชื่อช่าง)
    public function tech()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    // เชื่อมกับตาราง RequestSparePart (รายการอะไหล่ที่ถูกเบิกในงานนี้)
    public function spareParts()
    {
        return $this->hasMany(RequestSparePart::class, 'request_id');
    }
}