<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ชำระเงิน - เอส.เอ็ม.แอร์บ้าน</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-900">

    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-gray-800">แจ้งชำระเงิน</h1>
                <p class="text-gray-500 mt-2">รหัสงาน #{{ $job->id }} | บริการ: {{ $job->service->service_name }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                
                <div class="md:col-span-3 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                        <h3 class="text-lg font-bold mb-6 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-sm">1</span>
                            สรุปรายการค่าใช้จ่าย
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                                <span class="text-gray-600">{{ $job->service->service_name }}</span>
                                <span class="font-bold">{{ number_format($job->service->base_price, 2) }} บ.</span>
                            </div>

                            @if($job->spareParts->count() > 0)
                                @foreach($job->spareParts as $item)
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>⚙️ {{ $item->sparePart->part_name }} (x{{ $item->quantity }})</span>
                                    <span>{{ number_format($item->total_price, 2) }} บ.</span>
                                </div>
                                @endforeach
                            @endif

                            <div class="pt-6 mt-6 border-t-2 border-dashed border-gray-100 flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-800">ยอดชำระทั้งสิ้น</span>
                                <span class="text-3xl font-black text-blue-600">{{ number_format($job->total_price ?: $job->service->base_price, 2) }} <small class="text-sm">บาท</small></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-600 rounded-3xl shadow-lg p-8 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-4 italic">ช่องทางชำระเงิน</h3>
                            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl backdrop-blur-sm">
                                <div class="bg-white p-2 rounded-xl text-blue-600 font-black text-xs uppercase">PromptPay</div>
                                <div>
                                    <p class="text-xs opacity-80 text-blue-100">เลขบัญชี / เบอร์พร้อมเพย์</p>
                                    <p class="text-xl font-bold tracking-widest">08X-XXX-XXXX</p>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-blue-100">* ชื่อบัญชี: สมชาย (เอส.เอ็ม.แอร์บ้าน)</p>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100 sticky top-10">
                        <h3 class="text-lg font-bold mb-6 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-sm">2</span>
                            แจ้งโอนเงิน
                        </h3>

                        <form action="{{ route('payment.upload', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-3">รูปภาพสลิปโอนเงิน</label>
                                <div class="relative group">
                                    <input type="file" name="slip_image" id="slip_image" class="hidden" accept="image/*" required onchange="previewImage(this)">
                                    <label for="slip_image" class="cursor-pointer border-2 border-dashed border-gray-200 rounded-2xl p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-blue-300 transition-all">
                                        <div id="preview-container" class="hidden mb-3">
                                            <img id="preview-img" src="#" alt="Preview" class="max-h-40 rounded-lg shadow-sm">
                                        </div>
                                        <div id="upload-placeholder" class="text-center">
                                            <span class="text-4xl mb-2 block">📸</span>
                                            <span class="text-xs text-gray-400 font-bold uppercase">คลิกเพื่อเลือกรูป</span>
                                        </div>
                                    </label>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2">* รองรับไฟล์ JPG, PNG (ไม่เกิน 2MB)</p>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95">
                                ยืนยันการชำระเงิน
                            </button>
                        </form>

                        <a href="{{ route('track.index') }}" class="block text-center mt-6 text-sm text-gray-400 hover:text-gray-600 transition underline">
                            ยกเลิกและกลับไปหน้าเดิม
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview-img');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>