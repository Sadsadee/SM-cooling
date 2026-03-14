<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('หน้าควบคุมหลัก - Admin (ผู้ดูแลระบบ)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 font-bold text-lg text-blue-600">
                    ยินดีต้อนรับ แอดมิน {{ Auth::user()->name }}! 
                </div>
                <div class="p-6 text-gray-900 border-t">
                    ที่นี่คือศูนย์กลางสำหรับจัดการระบบของ SM Cooling Center <br>
                    เดี๋ยวเราจะมาเพิ่มปุ่ม จัดการคำร้อง, อนุมัติช่าง และกำหนดราคาอะไหล่ กันที่หน้านี้ครับ
                </div>
            </div>
        </div>
    </div>
</x-app-layout>