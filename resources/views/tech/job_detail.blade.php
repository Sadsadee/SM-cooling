<x-app-layout>
    <div class="bg-gray-50 min-h-screen pb-24">
        {{-- Header V3: เน้นความโปร่งและจัดระเบียบใหม่ --}}
        <div class="bg-blue-600 pt-14 pb-28 px-6 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            {{-- ลวดลายพื้นหลัง --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-400/20 rounded-full -ml-20 -mb-20 blur-2xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                {{-- ปุ่มย้อนกลับแบบลอย --}}
                <a href="{{ route('tech.dashboard') }}"
                    class="absolute -top-4 left-0 bg-white/20 p-3 rounded-2xl backdrop-blur-lg text-white active:scale-90 transition border border-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>

                <div class="text-center mt-4">
                    <p class="text-blue-200 text-[10px] font-black uppercase tracking-[0.3em] mb-2 opacity-80">JOB
                        DETAILS #{{ $job->id }}</p>
                    <h1 class="text-3xl font-black text-white leading-tight mb-4">{{ $job->service->service_name }}</h1>

                    <div
                        class="inline-flex items-center px-5 py-2 bg-black/20 backdrop-blur-md rounded-full border border-white/10">
                        <span
                            class="w-2 h-2 bg-yellow-400 rounded-full mr-3 animate-pulse shadow-[0_0_10px_rgba(250,204,21,0.8)]"></span>
                        <span
                            class="text-[11px] font-black uppercase tracking-widest text-white">{{ $job->status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto px-6 -mt-16 space-y-6 relative z-20">
            {{-- การ์ดลูกค้า: เน้นความคลีน --}}
            <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-blue-900/10 border border-white">
                <div class="flex items-center gap-5 mb-8">
                    <div
                        class="bg-blue-50 w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl shadow-inner">
                        👤</div>
                    <div class="flex-1">
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">{{ $job->customer->name }}</h3>
                        <div class="flex items-center text-blue-500 mt-1">
                            <span
                                class="text-[10px] font-black uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-md">Customer
                                ID #{{ $job->customer->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-5 rounded-[2rem] border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Location /
                            Address</p>
                        <p class="text-sm text-gray-700 font-bold leading-relaxed flex items-start">
                            <span class="mr-2 text-lg">📍</span>
                            {{ $job->customer->address_detail ?? $job->customer->address }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-[2rem] border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Issue / Problem
                        </p>
                        <p class="text-sm text-gray-500 font-bold italic leading-relaxed">
                            "{{ $job->problem_details ?: 'ไม่มีรายละเอียดปัญหา' }}"
                        </p>
                    </div>
                </div>

                <a href="tel:{{ $job->customer->phone }}"
                    class="w-full mt-8 bg-blue-600 text-white py-5 rounded-[2rem] font-black text-sm text-center flex items-center justify-center gap-3 shadow-xl shadow-blue-200 active:scale-95 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 2V3z">
                        </path>
                    </svg>
                    CALL CUSTOMER
                </a>
            </div>

            {{-- ส่วนของอะไหล่: ปรับให้ใช้ง่ายขึ้น --}}
            <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-blue-900/10 border border-white">
                <h3 class="font-black text-gray-800 mb-6 text-lg flex items-center">
                    <span
                        class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-3 text-xl">📦</span>
                    Inventory Check
                </h3>

                <form action="{{ route('tech.add_part', $job->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="spare_part_id"
                        class="w-full border-none bg-gray-50 rounded-2xl text-xs font-black focus:ring-2 focus:ring-orange-500 py-4 px-6 appearance-none shadow-inner"
                        required>
                        <option value="">-- SELECT SPARE PART --</option>
                        @foreach(\App\Models\SparePart::where('stock', '>', 0)->get() as $part)
                            <option value="{{ $part->id }}">{{ $part->part_name }} (LEFT: {{ $part->stock }})</option>
                        @endforeach
                    </select>

                    <div class="flex gap-3">
                        <input type="number" name="quantity" value="1" min="1"
                            class="w-24 border-none bg-gray-50 rounded-2xl text-sm font-black text-center py-4 shadow-inner">
                        <button type="submit"
                            class="flex-1 bg-gray-900 text-white font-black py-4 rounded-2xl shadow-xl text-[10px] tracking-[0.2em] uppercase active:scale-95 transition">
                            ADD ITEM
                        </button>
                    </div>
                </form>

                <div class="mt-8 space-y-3">
                    @php $totalPartsPrice = 0; @endphp
                    @forelse($job->spareParts as $item)
                        @php $totalPartsPrice += $item->total_price; @endphp
                        <div
                            class="flex justify-between items-center bg-gray-50/50 p-4 rounded-[1.5rem] border border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-gray-800">{{ $item->sparePart->part_name }}</span>
                                <span class="text-[9px] text-gray-400 font-black uppercase mt-1">QTY:
                                    {{ $item->quantity }}</span>
                            </div>
                            <span class="text-sm font-black text-blue-600">{{ number_format($item->total_price) }} บ.</span>
                        </div>
                    @empty
                        <div
                            class="text-center py-4 opacity-30 italic text-[10px] font-black uppercase tracking-widest text-gray-400">
                            No parts used yet</div>
                    @endforelse
                </div>
            </div>

            {{-- ยอดรวมสุทธิ --}}
            @php
                $basePrice = $job->service->base_price ?? 0;
                $netTotal = $basePrice + $totalPartsPrice;
            @endphp

            <div class="pb-12 space-y-4">
                <div class="bg-gray-900 rounded-[3rem] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/10 rounded-full -mr-16 -mt-16 blur-2xl">
                    </div>
                    <div class="flex justify-between items-end relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">Total Amount
                            </p>
                            <p class="text-4xl font-black text-yellow-400 tracking-tighter">
                                {{ number_format($netTotal) }} <span
                                    class="text-sm font-normal text-white opacity-40 ml-1">THB</span></p>
                        </div>
                        <div class="text-right pb-1">
                            <p class="text-[9px] text-gray-400 font-black">LABOR: {{ number_format($basePrice) }}</p>
                            <p class="text-[9px] text-gray-400 font-black mt-1">PARTS:
                                {{ number_format($totalPartsPrice) }}</p>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="openCompleteModal()"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-6 rounded-[3rem] font-black text-lg shadow-2xl shadow-green-200 flex items-center justify-center gap-3 active:scale-95 transition">
                    FINISH JOB 🚀
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: ปรับให้สวยแบบ Bottom Sheet --}}
    <div id="completeJobModal"
        class="fixed inset-0 bg-black/60 hidden items-end justify-center z-[100] backdrop-blur-md p-4 transition-all duration-500">
        <div
            class="bg-white p-10 rounded-t-[4rem] rounded-b-[2rem] w-full max-w-md animate-slide-up shadow-2xl border-t border-white">
            <div class="w-16 h-1.5 bg-gray-100 rounded-full mx-auto mb-10"></div>

            <div class="text-center mb-10">
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">Summary</h3>
                <p class="text-gray-400 text-xs font-black mt-2 uppercase tracking-widest">Confirm payment with customer
                </p>
            </div>

            <div class="bg-green-50 p-10 rounded-[3rem] border border-green-100 text-center mb-10 shadow-inner">
                <p class="text-[10px] text-green-700 font-black uppercase tracking-[0.3em] mb-3">Grand Total</p>
                <p class="text-6xl font-black text-green-600 tracking-tighter">{{ number_format($netTotal) }} <span
                        class="text-xl">฿</span></p>
            </div>

            <form action="{{ route('tech.complete_job', $job->id) }}" method="POST">
                @csrf
                <div class="mb-10">
                    <label class="block text-[10px] font-black text-gray-400 mb-4 uppercase tracking-[0.2em] ml-2">Job
                        Notes</label>
                    <textarea name="tech_notes" rows="3" placeholder="Explain what you've done..."
                        class="w-full border-none bg-gray-50 rounded-[2.5rem] py-5 px-8 focus:ring-2 focus:ring-green-500 text-sm font-bold shadow-inner"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeCompleteModal()"
                        class="py-5 bg-gray-50 rounded-[2.5rem] text-[10px] font-black text-gray-400 uppercase tracking-widest active:scale-95 transition">Back</button>
                    <button type="submit"
                        class="py-5 bg-green-600 rounded-[2.5rem] text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-green-100 active:scale-95 transition font-bold">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes slide-up {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        function openCompleteModal() {
            const modal = document.getElementById('completeJobModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCompleteModal() {
            const modal = document.getElementById('completeJobModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>