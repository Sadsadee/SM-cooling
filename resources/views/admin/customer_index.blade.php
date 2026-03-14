<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👥 จัดการข้อมูลผู้ใช้งาน
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            </div>

            {{-- ส่วนที่ 2: ตารางลูกค้า (Customers) --}}
            <div id="customer-section" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-gray-800 flex items-center">
                            <span class="p-2 bg-purple-100 rounded-lg mr-3">👤</span> รายชื่อลูกค้าในระบบ
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">ประวัติลูกค้าที่เคยใช้บริการซ่อม/ล้างแอร์</p>
                    </div>
                    <span class="bg-purple-600 text-white px-4 py-1 rounded-full text-xs font-bold shadow-lg shadow-purple-100">
                        {{ $customers->count() }} คน
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">ลูกค้า</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">เบอร์โทรศัพท์</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($customers as $cus)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-gray-700">{{ $cus->name }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-sm text-gray-500 font-medium">{{ $cus->phone }}</p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form action="{{ route('admin.users.destroy', $cus->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบประวัติลูกค้า?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-[10px] font-black hover:bg-red-500 hover:text-white transition">
                                            ลบประวัติ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>