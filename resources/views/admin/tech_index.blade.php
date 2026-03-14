<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👨‍🔧 จัดการข้อมูลทีมช่าง
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ✅ ส่วนที่ 1: ฟอร์มลงทะเบียนช่างใหม่ (เพิ่มเข้ามาตามที่คุณต้องการ) --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/5 border border-blue-50">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-blue-600 rounded-2xl text-white mr-4 shadow-lg shadow-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">ลงทะเบียนช่างใหม่</h3>
                        <p class="text-sm text-gray-400">สร้างบัญชีผู้ใช้งานให้ช่างเพื่อใช้รับงานผ่านมือถือ</p>
                    </div>
                </div>

                <form action="{{ route('admin.techs.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    @csrf
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-2">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" placeholder="ระบุชื่อช่าง" required
                            class="w-full border-gray-100 bg-gray-50 rounded-2xl py-3 px-5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-2">เบอร์โทรศัพท์
                            (Login)</label>
                        <input type="text" name="phone" placeholder="08XXXXXXXX" required
                            class="w-full border-gray-100 bg-gray-50 rounded-2xl py-3 px-5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-2">รหัสผ่านเริ่มต้น</label>
                        <input type="password" name="password" placeholder="ตั้งรหัส 6 ตัวขึ้นไป" required
                            class="w-full border-gray-100 bg-gray-50 rounded-2xl py-3 px-5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200 transition active:scale-95 flex justify-center items-center gap-2">
                        <span>✨ ลงทะเบียน</span>
                    </button>
                </form>
            </div>

            {{-- ✅ ส่วนที่ 2: รายชื่อช่างที่มีอยู่ --}}
            <div id="tech-section" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-gray-800 flex items-center">
                            <span class="p-2 bg-blue-100 rounded-lg mr-3">👨‍🔧</span> ช่างในระบบปัจจุบัน
                        </h3>
                    </div>
                    <span
                        class="bg-blue-600 text-white px-5 py-1.5 rounded-full text-xs font-black shadow-lg shadow-blue-100">
                        {{ $techs->count() }} คน
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                    ข้อมูลการเข้าใช้งาน / สถานะ</th>
                                <th
                                    class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">
                                    จัดการข้อมูล</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($techs as $tech)
                                            <tr class="hover:bg-blue-50/20 transition">
                                                <form action="{{ route('admin.users.update', $tech->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <td class="px-8 py-6">
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                                            {{-- สถานะ --}}
                                                            <div>
                                                                <label
                                                                    class="block text-[9px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-1">สถานะ</label>
                                                                <div class="flex items-center h-[46px]">
                                                                    @if($tech->status == 'available')
                                                                        <span
                                                                            class="inline-flex items-center bg-green-50 text-green-700 px-4 py-1.5 rounded-xl text-[10px] font-black border border-green-100 uppercase tracking-tighter">
                                                                            <span
                                                                                class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                                                            ว่าง / พร้อมรับงาน
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="inline-flex items-center bg-red-50 text-red-700 px-4 py-1.5 rounded-xl text-[10px] font-black border border-red-100 uppercase tracking-tighter">
                                                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                                            ติดงานซ่อม
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- ชื่อ --}}
                                                            <div>
                                                                <label
                                                                    class="block text-[9px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-1">ชื่อช่าง</label>
                                                                <input type="text" name="name" value="{{ $tech->name }}"
                                                                    class="w-full border-gray-100 bg-gray-50/50 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                                                            </div>
                                                            {{-- เบอร์ --}}
                                                            <div>
                                                                <label
                                                                    class="block text-[9px] font-black text-gray-400 mb-2 uppercase tracking-widest ml-1">เบอร์โทรศัพท์</label>
                                                                <input type="text" name="phone" value="{{ $tech->phone }}"
                                                                    class="w-full border-gray-100 bg-gray-50/50 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-8 py-6">
                                                        <div class="flex justify-center items-center gap-4">
                                                            <button type="submit"
                                                                class="bg-gray-900 hover:bg-black text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition active:scale-95">
                                                                อัปเดต
                                                            </button>
                                                </form>
                                                <form action="{{ route('admin.users.destroy', $tech->id) }}" method="POST"
                                                    onsubmit="return confirm('⚠️ คำเตือน: คุณต้องการลบช่างคนนี้ออกจากระบบใช่หรือไม่?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-300 hover:text-red-600 p-2 transition active:scale-90">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                </div>
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