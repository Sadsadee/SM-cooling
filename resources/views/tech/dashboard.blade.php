<x-app-layout>
    {{-- ✨ ส่วนที่ 1: เพิ่ม Form Logout (ซ่อนไว้) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <div class="bg-gray-50 min-h-screen pb-20">
        {{-- Header ส่วนหัว --}}
        <div class="bg-blue-600 pt-10 pb-20 px-6 rounded-b-[3rem] shadow-lg">
            <div class="flex justify-between items-center text-white mb-6">
                <div>
                    <p class="text-blue-100 text-sm">ยินดีต้อนรับช่าง</p>
                    <h1 class="text-2xl font-black">{{ Auth::user()->name }}</h1>
                </div>
                {{-- ✨ ส่วนที่ 2: แก้ไขไอคอนขวาบนให้กด Logout ได้ --}}
                <button onclick="if(confirm('ยืนยันการออกจากระบบ?')) document.getElementById('logout-form').submit();"
                    class="bg-white/20 p-3 rounded-2xl backdrop-blur-md active:scale-90 transition group">
                    <span class="text-2xl group-hover:hidden">👨‍🔧</span>
                    <span class="text-xl hidden group-hover:inline">🚪</span>
                </button>
            </div>

            {{-- การ์ดสรุปงานสั้นๆ --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase font-bold tracking-wider">งานวันนี้</p>
                    <p class="text-white text-2xl font-black mt-1">{{ $jobs->count() }} <span
                            class="text-sm font-normal">งาน</span></p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase font-bold tracking-wider">สถานะ</p>
                    <p class="text-white text-sm font-bold mt-2 flex items-center">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-ping"></span> พร้อมรับงาน
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto px-6 -mt-10 space-y-8">
            {{-- ส่วนของงานที่ได้รับมอบหมาย --}}
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-black text-gray-800">คิวงานของคุณ</h2>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">อัปเดตล่าสุด</span>
                </div>

                @forelse($jobs as $job)
                    <div
                        class="bg-white rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100 p-6 mb-4 relative overflow-hidden group active:scale-95 transition">
                        <div class="absolute top-0 right-0">
                            <span
                                class="bg-blue-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-2xl uppercase">
                                #{{ $job->id }}
                            </span>
                        </div>

                        <div class="flex items-start gap-4 mb-4">
                            <div class="bg-gray-50 p-3 rounded-2xl text-2xl">
                                {{ $job->service->service_name == 'ล้างแอร์ติดผนัง' ? '💦' : '🔧' }}
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-lg leading-tight">{{ $job->service->service_name }}
                                </h3>
                                <p class="text-gray-500 text-sm font-bold mt-1">👤 {{ $job->customer->name }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-3xl">
                            <div class="flex items-center text-sm text-gray-600 font-medium">
                                <span class="mr-3 text-lg">📍</span>
                                <span
                                    class="line-clamp-2 text-xs text-gray-500">{{ $job->customer->address ?? 'ไม่มีข้อมูลที่อยู่' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 font-medium">
                                <span class="mr-3 text-lg">⏰</span>
                                <span>{{ date('H:i', strtotime($job->appointment_time)) }} น.
                                    ({{ date('d M', strtotime($job->appointment_date)) }})</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($job->customer->address) }}"
                                target="_blank"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-2xl text-sm font-black flex items-center justify-center transition">
                                🗺️ แผนที่
                            </a>
                            <a href="{{ route('tech.requests.show', $job->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl text-sm font-black flex items-center justify-center shadow-lg shadow-blue-200 transition w-full">
                                ⚡ เริ่มงาน
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-gray-400 font-bold">ไม่มีงานค้างในขณะนี้</p>
                    </div>
                @endforelse
            </section>

            {{-- ส่วนของประวัติงาน --}}
            <section class="pb-10">
                <h2 class="text-lg font-black text-gray-800 mb-4">งานที่ทำสำเร็จแล้ว</h2>
                <div class="space-y-3">
                    @foreach($history as $done)
                        <div
                            class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-lg">
                                    ✅
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800">{{ $done->service->service_name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">
                                        {{ date('d/m/Y', strtotime($done->updated_at)) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-green-600 font-mono">
                                    {{ number_format($done->total_price) }} บ.
                                </p>
                                <p class="text-[9px] text-gray-300 font-bold uppercase">ปิดยอดแล้ว</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <div
        class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-lg border-t border-gray-100 px-10 py-4 flex justify-around items-center z-50">
        <a href="{{ route('tech.dashboard') }}"
            class="{{ request()->routeIs('tech.dashboard') ? 'text-blue-600' : 'text-gray-400' }} flex flex-col items-center">
            <span class="text-xl">🏠</span>
            <span class="text-[10px] font-bold mt-1 uppercase">งานหลัก</span>
        </a>
        <a href="#" class="text-gray-400 flex flex-col items-center">
            <span class="text-xl">📊</span>
            <span class="text-[10px] font-bold mt-1 uppercase">สรุปยอด</span>
        </a>
        {{-- ✨ ส่วนที่ 3: แก้ไขเมนูตั้งค่าให้กด Logout ได้ --}}
        <button
            onclick="if(confirm('คุณต้องการออกจากระบบใช่หรือไม่?')) document.getElementById('logout-form').submit();"
            class="text-red-400 flex flex-col items-center">
            <span class="text-xl">⚙️</span>
            <span class="text-[10px] font-bold mt-1 uppercase">ออกจากระบบ</span>
        </button>
    </div>
</x-app-layout>