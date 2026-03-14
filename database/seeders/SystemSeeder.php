<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\SparePart;
use Illuminate\Support\Facades\Hash;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้างอะไหล่ (ถ้าไม่มี)
        SparePart::firstOrCreate(['part_name' => 'คาปาซิเตอร์ (Cap Run)'], ['price' => 350, 'stock' => 50]);
        SparePart::firstOrCreate(['part_name' => 'น้ำยาแอร์ R32'], ['price' => 20, 'stock' => 1000]);
        SparePart::firstOrCreate(['part_name' => 'เซนเซอร์น้ำแข็ง'], ['price' => 250, 'stock' => 30]);

        // 2. สร้าง User หลัก (Admin, Tech, Customer)
        User::firstOrCreate(['email' => 'admin@test.com'], [
            'name' => 'แอดมิน สมชาย',
            'password' => Hash::make('12345678'),
            'phone' => '0811111111',
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'tech@test.com'], [
            'name' => 'ช่างเก่ง งานดี',
            'password' => Hash::make('12345678'),
            'phone' => '0822222222',
            'role' => 'tech',
        ]);

        // 3. สร้างบริการ
        Service::firstOrCreate(['service_name' => 'ล้างแอร์ติดผนัง'], ['base_price' => 500]);
        Service::firstOrCreate(['service_name' => 'ซ่อมแอร์น้ำหยด'], ['base_price' => 800]);
        Service::firstOrCreate(['service_name' => 'เติมน้ำยาแอร์'], ['base_price' => 500]);

        // 4. สร้างลูกค้า Dummy เพิ่มเติม
        $customers = [
            ['name' => 'คุณสมชาย ใจดี', 'phone' => '0812345678', 'email' => 'customer1@test.com'],
            ['name' => 'คุณวิภาดา รักเรียน', 'phone' => '0823456789', 'email' => 'customer2@test.com'],
            ['name' => 'คุณมานะ ขยันงาน', 'phone' => '0834567890', 'email' => 'customer3@test.com'],
        ];

        foreach ($customers as $c) {
            User::firstOrCreate(['email' => $c['email']], [
                'name' => $c['name'],
                'phone' => $c['phone'],
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'address_detail' => '123/45 หมู่บ้านทดสอบ จ.ปทุมธานี',
                'subdistrict' => 'บึงคำพร้อย',
                'district' => 'ลำลูกกา',
                'province' => 'ปทุมธานี',
            ]);
        }

        // 5. สุ่มสร้างรายการแจ้งซ่อม 10 ชุด
        $statuses = ['pending', 'awaiting_payment', 'paid', 'approved', 'in_progress', 'completed'];
        $customerIds = User::where('role', 'customer')->pluck('id')->toArray();
        $serviceIds = Service::pluck('id')->toArray();
        $techIds = User::where('role', 'tech')->pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            $status = $statuses[array_rand($statuses)];
            
            ServiceRequest::create([
                'customer_id' => $customerIds[array_rand($customerIds)],
                'service_id' => $serviceIds[array_rand($serviceIds)],
                'problem_details' => "รายการทดสอบที่ $i: ลูกค้าแจ้งอาการแอร์ไม่เย็น มีเสียงดังผิดปกติ",
                'appointment_date' => now()->addDays(rand(1, 10))->format('Y-m-d'),
                'appointment_time' => '10:00',
                'tech_id' => in_array($status, ['approved', 'in_progress', 'completed']) ? $techIds[array_rand($techIds)] : null,
                'status' => $status,
                'total_price' => rand(500, 2500),
                'created_at' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}