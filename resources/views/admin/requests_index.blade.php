<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ระบบจัดการคิวงาน SM Cooling Center') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        {{-- แจ้งเตือน Success --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">สำเร็จ!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ✨ ส่วนสถิติ Dashboard (4 การ์ด) ✨ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl p-6 shadow-lg shadow-blue-200 text-white flex flex-col justify-center relative overflow-hidden transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-xs font-bold mb-1 uppercase tracking-widest">รายได้รวม (งานเสร็จสิ้น)</p>
                        <p class="text-3xl font-black">฿{{ number_format($totalRevenue ?? 0) }}</p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-20">💰</span>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-3xl p-6 shadow-lg shadow-green-200 text-white flex flex-col justify-center relative overflow-hidden transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative z-10">
                        <p class="text-green-100 text-xs font-bold mb-1 uppercase tracking-widest">งานที่ปิดจ๊อบแล้ว</p>
                        <p class="text-3xl font-black">{{ number_format($completedCount ?? 0) }} <span class="text-sm font-bold opacity-80">งาน</span></p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-20">✅</span>
                </div>

                <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-3xl p-6 shadow-lg shadow-orange-200 text-white flex flex-col justify-center relative overflow-hidden transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative z-10">
                        <p class="text-orange-100 text-xs font-bold mb-1 uppercase tracking-widest">งานที่รอ/กำลังดำเนินการ</p>
                        <p class="text-3xl font-black">{{ number_format($activeCount ?? 0) }} <span class="text-sm font-bold opacity-80">งาน</span></p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-20">⏳</span>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl p-6 shadow-lg shadow-purple-200 text-white flex flex-col justify-center relative overflow-hidden transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative z-10">
                        <p class="text-purple-100 text-xs font-bold mb-1 uppercase tracking-widest">ช่างในระบบ</p>
                        <p class="text-3xl font-black">{{ number_format($techCount ?? 0) }} <span class="text-sm font-bold opacity-80">คน</span></p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-20">👨‍🔧</span>
                </div>
            </div>

            {{-- ส่วนที่ 1: Kanban Board 3 คอลัมน์ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                {{-- คอลัมน์ 1: งานเข้าใหม่ --}}
                <div class="space-y-4">
                    <h3 class="flex items-center text-lg font-black text-gray-700 px-2 uppercase tracking-wider">
                        <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                        🔔 งานเข้าใหม่ ({{ $pendingJobs->count() }})
                    </h3>
                    <div class="space-y-4 overflow-y-auto max-h-[70vh] pr-2">
                        @forelse($pendingJobs as $job)
                            <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] font-bold text-gray-400">#{{ $job->id }}</span>
                                    <span class="text-[10px] bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full font-bold">NEW</span>
                                </div>
                                <p class="font-black text-gray-800 text-lg leading-tight">{{ $job->customer->name }}</p>
                                {{-- เพิ่มเบอร์โทร --}}
                                <p class="text-xs font-bold text-blue-600 mb-1 underline"> {{ $job->customer->phone }}</p>
                                <p class="text-sm text-gray-500 font-bold mb-4">{{ $job->service->service_name }}</p>
                                
                                <div class="flex gap-2">
                                    <a href="tel:{{ $job->customer->phone }}" class="flex-1 bg-gray-100 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-gray-200 transition"> โทรออก</a>
                                    <form action="{{ route('admin.request_payment', $job->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                                            แจ้งจ่ายเงิน
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-white/50 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400 text-sm italic">ไม่มีงานใหม่</div>
                        @endforelse
                    </div>
                </div>

                {{-- คอลัมน์ 2: รอโอนเงิน --}}
                <div class="space-y-4">
                    <h3 class="flex items-center text-lg font-black text-gray-700 px-2 uppercase tracking-wider">
                        <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                        ⏳ รอโอนเงิน ({{ $waitingPaymentJobs->count() }})
                    </h3>
                    <div class="space-y-4 overflow-y-auto max-h-[70vh] pr-2">
                        @forelse($waitingPaymentJobs as $job)
                            <div class="bg-white p-5 rounded-3xl shadow-sm border border-red-50 relative hover:shadow-md transition">
                                <p class="font-bold text-gray-800">{{ $job->customer->name }}</p>
                                {{-- เพิ่มเบอร์โทร --}}
                                <p class="text-xs font-bold text-blue-600 underline"><a href="tel:{{ $job->customer->phone }}"> {{ $job->customer->phone }}</a></p>
                                <p class="text-xs text-gray-400 mb-4 mt-1">{{ $job->service->service_name }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-red-400 italic">{{ $job->updated_at->diffForHumans() }}</span>
                                    <a href="{{ route('admin.requests.show', $job->id) }}" class="text-[10px] font-bold text-blue-500 underline">ตรวจสลิป</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-300 py-10 italic">ไม่มีรายการรอลูกค้าโอน</p>
                        @endforelse
                    </div>
                </div>

                {{-- คอลัมน์ 3: พร้อมส่งช่าง --}}
                <div class="space-y-4">
                    <h3 class="flex items-center text-lg font-black text-gray-700 px-2 uppercase tracking-wider">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        💰 จ่ายแล้ว/ส่งช่าง ({{ $paidJobs->count() }})
                    </h3>
                    <div class="space-y-4 overflow-y-auto max-h-[70vh] pr-2">
                        @forelse($paidJobs as $job)
                            <div class="bg-white p-6 rounded-3xl shadow-xl border-2 border-green-500">
                                <p class="font-black text-gray-800 text-lg mb-1">{{ $job->customer->name }}</p>
                                {{-- เพิ่มเบอร์โทร --}}
                                <p class="text-xs font-bold text-blue-600 mb-2 underline"><a href="tel:{{ $job->customer->phone }}"> {{ $job->customer->phone }}</a></p>
                                <p class="text-sm text-gray-500 mb-5 leading-tight">{{ $job->service->service_name }}</p>
                                
                                <form action="{{ route('admin.requests.assign', $job->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">เลือกช่างที่จะเข้างาน</label>
                                    <select name="tech_id" required class="w-full rounded-xl border-gray-200 text-sm">
                                        <option value="">-- เลือกช่าง --</option>
                                        @foreach(\App\Models\User::where('role', 'tech')->get() as $tech)
                                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-green-700 transition">
                                        👨‍🔧 มอบหมายช่าง
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-center text-gray-300 py-10 italic">ไม่มีงานที่พร้อมส่งช่าง</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ส่วนที่ 2: ตารางประวัติงาน --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-6 px-2">📦 ประวัติงานที่ส่งช่างแล้ว/เสร็จแล้ว</h3>
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">รายละเอียดงาน</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ลูกค้า</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ช่างผู้รับผิดชอบ</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">สถานะปัจจุบัน</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($historyJobs as $job)
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-blue-600 leading-tight">{{ $job->service->service_name }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter">ID: #{{ $job->id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-800 font-bold">{{ $job->customer->name }}</p>
                                    {{-- เพิ่มเบอร์โทรในตารางประวัติ --}}
                                    <p class="text-xs text-blue-500 underline"><a href="tel:{{ $job->customer->phone }}">{{ $job->customer->phone }}</a></p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-2">
                                        <span class="text-sm font-bold text-gray-800">{{ $job->tech->name ?? 'ยังไม่ระบุ' }}</span>
                                        
                                        @if($job->tech_id)
                                            <button type="button" onclick="openPasswordModal({{ $job->tech_id }}, '{{ $job->tech->name }}')" class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-600 border border-orange-200 rounded-lg text-[10px] font-bold hover:bg-orange-500 hover:text-white transition">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                                ตั้งรหัสผ่านใหม่
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $colors = [
                                            'approved' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                            'in_progress' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                            'completed' => 'bg-green-100 text-green-700 border border-green-200',
                                        ];
                                        $style = $colors[$job->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider block w-fit mx-auto {{ $style }}">
                                        {{ $job->status }}
                                    </span>

                                    @if(in_array($job->status, ['completed', 'approved', 'paid_confirmed']))
                                    <div class="mt-2 text-center">
                                        <a href="{{ route('admin.requests.receipt', $job->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-gray-800 text-white rounded text-[10px] font-bold hover:bg-black transition shadow-sm">
                                            🖨️ ใบเสร็จ
                                        </a>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Popup สำหรับตั้งรหัสผ่าน --}}
    <div id="passwordModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md transform scale-100 transition-transform">
            <h3 class="text-xl font-bold mb-2 text-gray-800 flex items-center">
                <span class="text-2xl mr-2">🔑</span> ตั้งรหัสผ่านใหม่
            </h3>
            <p class="text-sm text-gray-500 mb-6">บัญชีช่าง: <span id="modalTechName" class="font-bold text-blue-600"></span></p>
            
            <form id="passwordForm" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">รหัสผ่านใหม่ (อย่างน้อย 6 ตัวอักษร)</label>
                    <input type="text" name="new_password" placeholder="พิมพ์รหัสผ่านใหม่ที่นี่..." required class="w-full border-gray-300 rounded-xl py-3 px-4 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-800 font-medium bg-gray-50">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePasswordModal()" class="px-5 py-2.5 bg-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-200 transition">ยกเลิก</button>
                    <button type="submit" class="px-5 py-2.5 bg-orange-500 rounded-xl text-sm font-bold text-white hover:bg-orange-600 shadow-lg shadow-orange-200 transition">💾 บันทึกรหัสผ่าน</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPasswordModal(techId, techName) {
            const modal = document.getElementById('passwordModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalTechName').innerText = techName;
            document.getElementById('passwordForm').action = `/admin/tech/${techId}/update-password`;
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>