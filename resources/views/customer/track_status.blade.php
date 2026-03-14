<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ติดตามสถานะ / ชำระเงิน - เอส.เอ็ม.แอร์บ้าน</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    
    <div class="min-h-screen py-10">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700">ติดตามสถานะงาน / ชำระเงิน</h1>
            <p class="text-gray-600 mt-2 text-lg">กรอกเบอร์โทรศัพท์ของคุณเพื่อดูประวัติและสถานะงาน</p>
        </div>

        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
                        <p class="font-bold">ขออภัย</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
                        <p class="font-bold">สำเร็จ</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <form action="{{ route('track.search') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            📞
                        </span>
                        <input type="text" name="phone" 
                            class="shadow-sm border border-gray-200 rounded-xl w-full py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all" 
                            placeholder="กรอกเบอร์โทรศัพท์" 
                            maxlength="10" 
                            value="{{ request('phone') ?? ($customer->phone ?? '') }}" 
                            required>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-lg whitespace-nowrap">
                        🔍 ค้นหา
                    </button>
                </form>
            </div>
        </div>

        @if(isset($requests))
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 animate-fadeIn">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">
                        ประวัติการแจ้งงานของ: {{ $customer->name ?? 'ลูกค้า' }}
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase">วันที่ / รหัสงาน</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase">บริการ</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase">ช่างดูแล</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase">สถานะ</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($requests as $req)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($req->created_at)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">#{{ $req->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-blue-600">{{ $req->service->service_name ?? 'ล้างแอร์/ซ่อมแอร์' }}</div>
                                    <div class="text-xs text-gray-500">รวม: {{ number_format($req->total_price ?: $req->service->base_price, 2) }} บ.</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                    {{ $req->tech->name ?? 'กำลังจัดสรรช่าง' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'รอรับเรื่อง'],
                                            'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'รอช่างดำเนินการ'],
                                            'in_progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'กำลังดำเนินการ'],
                                            'completed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'ซ่อมเสร็จสิ้น'],
                                            'paid' => ['bg' => 'bg-green-600', 'text' => 'text-white', 'label' => 'ชำระเงินแล้ว'],
                                        ];
                                        $current = $statusConfig[$req->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => $req->status];
                                    @endphp
                                    <span class="{{ $current['bg'] }} {{ $current['text'] }} px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                        {{ $current['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
    @if($req->status == 'awaiting_payment')
        {{-- กรณีแอดมินโทรคอนเฟิร์มแล้ว ให้ลูกค้าโอนเงินมัดจำ/ค่าบริการก่อน --}}
        <a href="{{ route('payment.form', $req->id) }}" 
           class="inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md animate-pulse transition-all">
            💳 โอนเงินยืนยันคิว
        </a>
    @elseif($req->status == 'completed')
        {{-- กรณีช่างซ่อมเสร็จแล้ว (เช่นงานซ่อมที่เก็บหน้างาน) ให้ลูกค้าโอนเงินส่วนที่เหลือ --}}
        <a href="{{ route('payment.form', $req->id) }}" 
           class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all">
            📄 ชำระเงิน/ดูบิล
        </a>
    @elseif($req->status == 'paid' || $req->status == 'paid_confirmed')
        {{-- ถ้าจ่ายแล้ว ให้ขึ้นสถานะขอบคุณ --}}
        <span class="text-green-600 font-bold text-sm">✨ ชำระเงินสำเร็จ</span>
    @else
        <span class="text-gray-300 text-sm italic">รอแอดมินยืนยัน</span>
    @endif
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                    ไม่พบประวัติการแจ้งงานสำหรับเบอร์โทรนี้
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-12 text-center">
            <a href="/" class="text-gray-400 hover:text-blue-600 transition flex items-center justify-center gap-2">
                🏠 กลับสู่หน้าหลัก
            </a>
        </div>

    </div>

</body>
</html>