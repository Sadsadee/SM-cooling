<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน #{{ $job->id }} - SM Cooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* จัดฟอร์แมตสำหรับตอนกด Print ให้เป็น A4 */
        @media print {
            @page { size: A4; margin: 1cm; }
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-border { border: 1px solid #e5e7eb !important; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 font-sans">
    
    <div class="max-w-4xl mx-auto mb-6 flex justify-between no-print px-4">
        <a href="{{ route('admin.requests.show', $job->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold">⬅️ กลับหน้ารายละเอียด</a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold flex items-center shadow-lg">
            🖨️ พิมพ์ใบเสร็จ (PDF)
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-12 shadow-xl print-border">
        
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-blue-800 tracking-wider">SM COOLING CENTER</h1>
                <p class="text-sm text-gray-600 mt-2">123/45 ถนนทดสอบ ตำบลบึงคำพร้อย อำเภอลำลูกกา</p>
                <p class="text-sm text-gray-600">จังหวัดปทุมธานี 12150</p>
                <p class="text-sm text-gray-600 font-bold mt-1">โทร: 081-111-1111</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800">ใบเสร็จรับเงิน / ใบแจ้งหนี้</h2>
                <h3 class="text-lg text-gray-500">(Receipt / Invoice)</h3>
                <div class="mt-4 text-sm bg-gray-50 p-3 rounded-lg border border-gray-200 inline-block text-left">
                    <p><span class="font-bold">เลขที่ (No.):</span> INV-{{ date('Ym') }}-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <p><span class="font-bold">วันที่ (Date):</span> {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between mb-8">
            <div class="w-2/3 pr-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">ลูกค้า (Customer)</p>
                <p class="font-bold text-lg text-gray-800">{{ $job->customer->name }}</p>
                <p class="text-sm text-gray-600">{{ $job->customer->address_detail ?? '-' }}</p>
                <p class="text-sm text-gray-600">โทร: {{ $job->customer->phone }}</p>
            </div>
            <div class="w-1/3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">ช่างผู้รับผิดชอบ (Technician)</p>
                <p class="font-bold text-gray-800">{{ $job->tech->name ?? 'ยังไม่ระบุ' }}</p>
            </div>
        </div>

        <table class="w-full text-left mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-3 px-4 font-bold text-sm w-16 text-center border border-gray-800">ลำดับ</th>
                    <th class="py-3 px-4 font-bold text-sm border border-gray-800">รายการ (Description)</th>
                    <th class="py-3 px-4 font-bold text-sm text-center w-24 border border-gray-800">จำนวน</th>
                    <th class="py-3 px-4 font-bold text-sm text-right w-32 border border-gray-800">ราคา/หน่วย</th>
                    <th class="py-3 px-4 font-bold text-sm text-right w-32 border border-gray-800">จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-3 px-4 border border-gray-200 text-center">1</td>
                    <td class="py-3 px-4 border border-gray-200 font-bold">{{ $job->service->service_name }} <span class="text-xs text-gray-500 font-normal block">{{ $job->problem_details }}</span></td>
                    <td class="py-3 px-4 border border-gray-200 text-center">1</td>
                    <td class="py-3 px-4 border border-gray-200 text-right">{{ number_format($job->service->base_price) }}</td>
                    <td class="py-3 px-4 border border-gray-200 text-right font-bold">{{ number_format($job->service->base_price) }}</td>
                </tr>

                @php 
                    $partsTotal = 0; 
                    $index = 2;
                @endphp
                @foreach($job->spareParts as $item)
                    @php $partsTotal += $item->total_price; @endphp
                    <tr>
                        <td class="py-3 px-4 border border-gray-200 text-center text-sm">{{ $index++ }}</td>
                        <td class="py-3 px-4 border border-gray-200 text-sm">อะไหล่: {{ $item->sparePart->part_name }}</td>
                        <td class="py-3 px-4 border border-gray-200 text-center text-sm">{{ $item->quantity }}</td>
                        <td class="py-3 px-4 border border-gray-200 text-right text-sm">{{ number_format($item->sparePart->price) }}</td>
                        <td class="py-3 px-4 border border-gray-200 text-right text-sm">{{ number_format($item->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-16">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">รวมเป็นเงิน (Subtotal)</span>
                    <span class="font-bold">{{ number_format($job->service->base_price + $partsTotal) }} บาท</span>
                </div>
                <div class="flex justify-between py-3 border-b-2 border-gray-800 bg-blue-50 px-2 mt-2">
                    <span class="font-bold text-blue-800">ยอดเงินสุทธิ (Net Total)</span>
                    <span class="font-black text-blue-800 text-xl">{{ number_format($job->total_price ?: ($job->service->base_price + $partsTotal)) }} บาท</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between text-center pt-8 mt-8 text-sm">
            <div class="w-1/3">
                <div class="border-b border-gray-400 w-3/4 mx-auto mb-2"></div>
                <p>ผู้รับเงิน (Collector)</p>
                <p class="text-gray-400 text-xs mt-1">วันที่ _______/_______/_______</p>
            </div>
            <div class="w-1/3">
                <div class="border-b border-gray-400 w-3/4 mx-auto mb-2"></div>
                <p>ผู้รับบริการ (Customer)</p>
                <p class="text-gray-400 text-xs mt-1">วันที่ _______/_______/_______</p>
            </div>
        </div>
        
    </div>
</body>
</html>