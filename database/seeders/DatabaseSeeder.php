<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ดึงไอดีลูกค้า (customer) และ บริการ (service) ที่มีในระบบ
        $customers = User::where('role', 'customer')->pluck('id')->toArray();
        $services = Service::pluck('id')->toArray();

        // ปัญหาจำลองให้ดูสมจริง (ผมเพิ่มให้มันดูหลากหลายขึ้นครับ)
        $problems = [
            'แอร์ไม่เย็นเลย มีแต่ลมร้อน', 
            'แอร์น้ำหยดซึมฝ้า รบกวนด่วนครับ', 
            'เปิดไม่ติด ไฟไทม์เมอร์กะพริบ', 
            'ล้างทำความสะอาดทั่วไป ไม่ได้ล้างมาปีนึงแล้ว', 
            'แอร์มีกลิ่นอับชื้นมาก',
            'คอมเพรสเซอร์ด้านนอกเสียงดังผิดปกติ',
            'ลมแอร์ออกเบามาก ฝุ่นน่าจะตัน',
            'ย้ายแอร์จากห้องนอนไปห้องนั่งเล่น',
            'แอร์เปิดติดบ้างไม่ติดบ้าง รีโมทกดไม่ค่อยติด',
            'ท่อน้ำทิ้งตัน น้ำล้นถาดแอร์'
        ];

        // เช็คว่ามีข้อมูลพื้นฐานพร้อมไหมก่อนสร้างงาน
        if (!empty($customers) && !empty($services)) {
            // ✨ ปรับจากเลข 5 เป็น 10 เพื่อให้ได้ 10 คิวตามที่ลูกพี่ต้องการ
            for ($i = 1; $i <= 10; $i++) {
                ServiceRequest::create([
                    'customer_id' => $customers[array_rand($customers)],
                    'service_id' => $services[array_rand($services)],
                    
                    // ⚠️ หมายเหตุ: เช็คชื่อคอลัมน์ใน Database ลูกพี่ด้วยนะครับ 
                    // ถ้าใน Migration ลูกพี่ตั้งชื่อว่า 'note' ให้เปลี่ยนคำว่า 'problem_details' เป็น 'note' ครับ
                    'problem_details' => $problems[array_rand($problems)], 
                    
                    // นัดหมายสุ่มล่วงหน้า 0-5 วันให้คิวมันกระจายตัวสวยๆ
                    'appointment_date' => Carbon::now()->addDays(rand(0, 5))->format('Y-m-d'), 
                    
                    // สุ่มเวลา 09:00 - 16:00
                    'appointment_time' => sprintf('%02d:00:00', rand(9, 16)), 
                    
                    'status' => 'pending', // สถานะ งานเข้าใหม่
                ]);
            }
            echo "✅ สร้างใบแจ้งซ่อมจำลอง 10 คิวสำเร็จ!\n";
        } else {
            echo "⚠️ กรุณาเพิ่มข้อมูลลูกค้าและบริการก่อนนะครับ!\n";
        }
    }
}