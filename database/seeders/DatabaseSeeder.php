<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // สั่งให้รันไฟล์ SystemSeeder ที่มีข้อมูล 10 ชุด อะไหล่ และบริการ
        $this->call([
            SystemSeeder::class,
        ]);
    }
}