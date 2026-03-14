<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('งานที่ได้รับมอบหมาย (ช่าง: ' . Auth::user()->name . ')') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">สำเร็จ!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="min-w-full bg-white border border-gray-200" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead style="background-color: #f3f4f6;">
                            <tr>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">รหัสงาน</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">ข้อมูลและที่อยู่ลูกค้า</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">บริการและอาการเสีย</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">สถานะ</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; vertical-align: top;">#{{ $req->id }}</td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <strong>👤 {{ $req->customer->name ?? 'ไม่ระบุ' }}</strong><br>
                                    📞 {{ $req->customer->phone ?? '-' }}<br>
                                    📍 <span style="font-size: 13px; color: #555;">{{ $req->customer->address_detail }} ต.{{ $req->customer->subdistrict }} อ.{{ $req->customer->district }} จ.{{ $req->customer->province }} {{ $req->customer->zipcode }}</span>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <span style="color: #2563eb; font-weight: bold;">{{ $req->service->service_name ?? '-' }}</span><br>
                                    <span style="font-size: 13px; color: #d97706;">อาการ: {{ $req->problem_details ?: 'ไม่ระบุ' }}</span>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    @if($req->status == 'in_progress')
                                        <span style="background: #bfdbfe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">กำลังดำเนินการ</span>
                                    @elseif($req->status == 'completed')
                                        <span style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">จบงานแล้ว</span>
                                    @else
                                        <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">{{ $req->status }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center; vertical-align: top;">
                                    @if($req->status == 'in_progress')
                                        <form action="{{ route('tech.requests.complete', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onclick="return confirm('ยืนยันการจบงานนี้ใช่หรือไม่?');">
                                                ✅ แจ้งจบงาน
                                            </button>
                                        </form>
                                    @elseif($req->status == 'completed')
                                        <span style="color: #166534; font-weight: bold;">✔ เรียบร้อย</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($requests->isEmpty())
                            <tr>
                                <td colspan="5" style="padding: 16px; text-align: center; color: #6b7280;">ตอนนี้คุณยังไม่มีงานที่ได้รับมอบหมายครับ</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>