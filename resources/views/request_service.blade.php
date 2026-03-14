<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('แบบฟอร์มแจ้งซ่อม / ล้างแอร์') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mb-4">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-sm" role="alert">
                    <strong class="font-bold">สำเร็จ!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- แสดง Error รวมด้านบน (ถ้ามี) --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl mb-4">
                    <p class="font-bold">กรุณาตรวจสอบข้อมูล:</p>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-8 border border-gray-100">
                
                <form action="{{ route('request.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h3 class="text-lg font-black mb-6 text-blue-700 flex items-center">
                        <span class="bg-blue-100 p-2 rounded-lg mr-3 text-sm">👤</span> ข้อมูลลูกค้า
                    </h3>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">เบอร์โทรศัพท์ (10 หลัก)</label>
                        <input type="text" id="phone_input" name="phone" 
                            class="shadow-sm border-gray-200 rounded-2xl w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all text-lg font-bold" 
                            maxlength="10" placeholder="08XXXXXXXX" value="{{ old('phone') }}" required>
                        <p class="text-[10px] text-blue-400 mt-1 font-bold">* พิมพ์ครบ 10 หลักเพื่อค้นหาที่อยู่เดิมอัตโนมัติ</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">ชื่อ-นามสกุล</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                class="border-gray-200 rounded-xl w-full py-2.5 px-4 bg-gray-50 focus:bg-white transition-all" required>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">บ้านเลขที่ / ซอย / ถนน</label>
                            <input type="text" id="address_detail" name="address_detail" value="{{ old('address_detail') }}"
                                class="border-gray-200 rounded-xl w-full py-2.5 px-4 bg-gray-50 focus:bg-white transition-all" required>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">ตำบล / แขวง</label>
                            <input type="text" id="subdistrict" name="subdistrict" value="{{ old('subdistrict') }}"
                                class="border-gray-200 rounded-xl w-full py-2.5 px-4 bg-gray-50 focus:bg-white transition-all" required>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">อำเภอ / เขต</label>
                            <input type="text" id="district" name="district" value="{{ old('district') }}"
                                class="border-gray-200 rounded-xl w-full py-2.5 px-4 bg-gray-50 focus:bg-white transition-all" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-600 text-xs font-bold mb-2">จังหวัด</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}"
                                class="border-gray-200 rounded-xl w-full py-2.5 px-4 bg-gray-50 focus:bg-white transition-all" required>
                        </div>
                    </div>

                    <h3 class="text-lg font-black mb-6 text-blue-700 flex items-center">
                        <span class="bg-blue-100 p-2 rounded-lg mr-3 text-sm">🛠️</span> รายละเอียดบริการ
                    </h3>

                    <div class="mb-6">
                        <label class="block text-gray-600 text-xs font-bold mb-2 uppercase tracking-wide">ประเภทบริการ</label>
                        <select name="service_id" id="service_select" class="w-full rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-blue-500" required onchange="togglePaymentSection()">
                            <option value="">-- เลือกบริการ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-name="{{ $service->service_name }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }} ({{ number_format($service->base_price) }} บาท)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-600 text-xs font-bold mb-2">รายละเอียดเพิ่มเติม / อาการเสีย</label>
                        <textarea name="problem_details" class="w-full rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-blue-500" rows="3" placeholder="ระบุอาการเบื้องต้น...">{{ old('problem_details') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">วันที่นัดหมาย</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" 
                                class="w-full rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-blue-500" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs font-bold mb-2">เวลาที่สะดวก</label>
                            <input type="time" name="appointment_time" value="{{ old('appointment_time') }}"
                                class="w-full rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    {{-- ส่วนการจ่ายเงิน --}}
                    <div id="payment_section" style="{{ str_contains(old('service_name_text', ''), 'ล้าง') ? 'display: block;' : 'display: none;' }}" 
                         class="mb-10 p-6 bg-blue-50 border-2 border-dashed border-blue-200 rounded-3xl text-center shadow-inner">
                        <h3 class="font-black text-blue-800 mb-4 text-lg">💰 ชำระมัดจำค่าบริการล้างแอร์ (500 บาท)</h3>
                        <img src="https://promptpay.io/0812345678/500.png" class="mx-auto w-56 mb-4 rounded-2xl shadow-xl border-4 border-white">
                        <p class="text-sm text-blue-600 font-bold mb-6 italic">ชื่อบัญชี: สมชาย (เอส.เอ็ม.คูลลิ่งเซ็นเตอร์)</p>
                        
                        <div class="text-left bg-white p-4 rounded-2xl border border-blue-100 shadow-sm">
                            <label class="block font-bold text-gray-700 mb-2 text-sm">แนบสลิปการโอนเงิน</label>
                            <input type="file" name="slip_image" id="slip_input" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform active:scale-95 text-lg uppercase tracking-widest">
                        ยืนยันการส่งคำร้อง
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript ดึงข้อมูลจากเบอร์โทร --}}
    <script>
        const phoneInput = document.getElementById('phone_input');
        
        phoneInput.addEventListener('keyup', function() {
            let phone = this.value;
            if (phone.length === 10) {
                // เปลี่ยนไปเรียกใช้ Route check-phone ที่เราสร้างไว้ใน Controller
                fetch(`/check-phone?phone=${phone}`)
                    .then(response => response.json())
                    .then(result => {
                        if (result.found) {
                            // ถ้าเจอข้อมูล ให้กรอกลงช่องต่างๆ ทันที
                            document.getElementById('name').value = result.data.name;
                            document.getElementById('address_detail').value = result.data.address_detail || '';
                            document.getElementById('subdistrict').value = result.data.subdistrict || '';
                            document.getElementById('district').value = result.data.district || '';
                            document.getElementById('province').value = result.data.province || '';
                            
                            // เปลี่ยนสีพื้นหลังให้รู้ว่าดึงข้อมูลมาแล้ว
                            const inputs = ['name', 'address_detail', 'subdistrict', 'district', 'province'];
                            inputs.forEach(id => {
                                document.getElementById(id).classList.add('bg-green-50', 'border-green-200');
                            });
                        }
                    });
            }
        });

        function togglePaymentSection() {
            const select = document.getElementById('service_select');
            const selectedText = select.options[select.selectedIndex].getAttribute('data-name') || "";
            const paymentSection = document.getElementById('payment_section');
            const slipInput = document.getElementById('slip_input');

            if (selectedText.includes('ล้าง')) {
                paymentSection.style.display = 'block';
                //slipInput.setAttribute('required', 'required'); // คุณจะบังคับโอนเลยหรือไม่ขึ้นอยู่กับคุณ
            } else {
                paymentSection.style.display = 'none';
                slipInput.removeAttribute('required');
            }
        }
    </script>
</x-app-layout>