<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะงานของคุณ - SM Cooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4">
        
        <div class="flex items-center justify-between mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-xl font-black text-gray-800">สวัสดี, {{ $customer->name }} 👋</h1>
                <p class="text-gray-500 text-sm mt-1">📞 {{ $customer->phone }}</p>
            </div>
            <a href="{{ route('track.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-600 transition">
                กลับหน้าค้นหา
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-2xl font-bold flex items-center shadow-sm">
                <span class="text-xl mr-2">✅</span> {{ session('success') }}
            </div>
        @endif

        <h2 class="text-lg font-bold text-gray-700 mb-4 px-2">รายการแจ้งซ่อมของคุณ</h2>
        <div class="space-y-6">
            @forelse($jobs as $job)
                <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                        <div>
                            <h3 class="font-bold text-lg text-blue-600">{{ $job->service->service_name }}</h3>
                            <p class="text-xs text-gray-400 mt-1">รหัสงาน: #{{ $job->id }} | วันที่แจ้ง: {{ $job->created_at->format('d/m/Y') }}</p>
                        </div>
                        
                        {{-- แปลงสถานะเป็นภาษาไทยและใส่สี --}}
                        @php
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-600',
                                'awaiting_payment' => 'bg-red-100 text-red-600 animate-pulse border border-red-200',
                                'paid' => 'bg-orange-100 text-orange-600',
                                'approved' => 'bg-blue-100 text-blue-600',
                                'in_progress' => 'bg-yellow-100 text-yellow-700',
                                'completed' => 'bg-green-100 text-green-700',
                            ];
                            $statusTexts = [
                                'pending' => 'รอแอดมินรับเรื่อง',
                                'awaiting_payment' => 'รอคุณชำระเงิน',
                                'paid' => 'กำลังตรวจสอบสลิป',
                                'approved' => 'กำลังจัดเตรียมช่าง',
                                'in_progress' => 'ช่างกำลังเดินทาง/ปฏิบัติงาน',
                                'completed' => 'งานเสร็จสิ้น',
                            ];
                        @endphp
                        <span class="px-4 py-2 rounded-full text-xs font-black text-center {{ $statusColors[$job->status] ?? 'bg-gray-100' }}">
                            {{ $statusTexts[$job->status] ?? $job->status }}
                        </span>
                    </div>

                    <div class="p-6 bg-gray-50/50">
                        <p class="text-sm text-gray-700 mb-2"><span class="font-bold">อาการเบื้องต้น:</span> {{ $job->problem_details ?: '-' }}</p>
                        <p class="text-sm text-gray-700"><span class="font-bold">ยอดเงินสุทธิ:</span> <span class="text-blue-600 font-bold">{{ number_format($job->total_price ?: $job->service->base_price) }} บาท</span></p>

                        {{-- 🛑 ฟอร์มอัปโหลดสลิป (จะแสดงเฉพาะตอนสถานะเป็น awaiting_payment) --}}
                        @if($job->status === 'awaiting_payment')
                            <div class="mt-6 p-5 bg-white border-2 border-dashed border-red-300 rounded-2xl">
                                <p class="text-sm font-bold text-red-600 mb-4 flex items-center">
                                    <span class="mr-2 text-lg">🚨</span> กรุณาแนบหลักฐานการโอนเงิน เพื่อยืนยันคิวงาน
                                </p>
                                <form action="{{ route('payment.upload', $job->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                                    @csrf
                                    <input type="file" name="slip_image" accept="image/jpeg, image/png, image/jpg" required
                                        class="flex-1 block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition cursor-pointer">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl shadow-md transition">
                                        📤 อัปโหลดสลิป
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-gray-400 font-bold">ยังไม่มีประวัติการแจ้งซ่อม</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>