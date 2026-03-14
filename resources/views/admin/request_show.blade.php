<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-3xl p-8">
                <h2 class="text-2xl font-bold mb-6">รายละเอียดงาน #{{ $job->id }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs text-gray-400 uppercase font-bold">ลูกค้า</p>
                        <p class="font-bold">{{ $job->customer->name }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs text-gray-400 uppercase font-bold">ยอดเงินรวม</p>
                        <p class="font-bold text-blue-600">
                            {{ number_format($job->total_price ?: $job->service->base_price, 2) }} บาท</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs text-gray-400 uppercase font-bold">สถานะงาน</p>
                        <span
                            class="text-sm font-bold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">{{ $job->status }}</span>
                    </div>
                </div>

                {{-- ส่วนข้อมูลช่างและการจัดการรหัสผ่าน --}}
                @if($job->tech)
                    <div
                        class="mb-8 p-6 bg-orange-50 rounded-3xl border border-orange-100 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-orange-400 uppercase font-bold">ช่างที่รับผิดชอบ</p>
                            <p class="text-lg font-black text-gray-800">{{ $job->tech->name }}</p>
                            <p class="text-xs text-gray-500">📞 {{ $job->tech->phone }}</p>
                        </div>

                    </div>
                @endif

                <div class="border-t pt-8">
                    <h3 class="text-lg font-bold mb-4">📸 หลักฐานการโอนเงิน</h3>
                    @if($job->slip_filename)
                        <div class="mb-6">
                            <img src="{{ asset('slips/' . $job->slip_filename) }}"
                                class="max-w-xs rounded-2xl shadow-lg border border-gray-200">
                        </div>

                        @if($job->status == 'paid')
                            <form action="{{ route('admin.requests.confirm_payment', $job->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg transition duration-200">
                                    ✅ ยืนยันยอดเงินถูกต้อง
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="text-gray-400 italic">ลูกค้ายังไม่ได้อัปโหลดสลิป</p>
                    @endif
                </div>

                <div class="mt-12 flex justify-between items-center border-t pt-6">
                    <a href="{{ route('admin.requests.receipt', $job->id) }}" target="_blank"
                        class="bg-gray-800 hover:bg-black text-white px-6 py-3 rounded-2xl font-bold flex items-center transition shadow-lg">
                        🖨️ พิมพ์ใบเสร็จรับเงิน
                    </a>
                    <a href="{{ route('admin.requests') }}"
                        class="text-gray-400 hover:text-gray-600 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        กลับหน้าจัดการคำร้อง
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>