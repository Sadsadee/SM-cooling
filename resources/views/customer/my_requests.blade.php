<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ประวัติการแจ้งงานของฉัน') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="min-w-full bg-white border border-gray-200" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead style="background-color: #f3f4f6;">
                            <tr>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">วันที่แจ้ง</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">บริการ</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">ช่างที่รับผิดชอบ</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">สถานะ</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: center;">ชำระเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; color: #555;">{{ \Carbon\Carbon::parse($req->created_at)->format('d/m/Y') }}<br><span style="font-size: 12px;">(รหัส: #{{ $req->id }})</span></td>
                                <td style="padding: 12px;">
                                    <strong style="color: #2563eb;">{{ $req->service->service_name ?? '-' }}</strong><br>
                                    <span style="font-size: 13px; color: #666;">ราคาเริ่มต้น: {{ number_format($req->service->base_price, 2) }} บ.</span>
                                </td>
                                <td style="padding: 12px;">{{ $req->tech->name ?? 'รอจัดสรรช่าง' }}</td>
                                <td style="padding: 12px;">
                                    @if($req->status == 'pending')
                                        <span style="background: #fef08a; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">รอแอดมินอนุมัติ</span>
                                    @elseif($req->status == 'approved')
                                        <span style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">รับเรื่องแล้ว (รอช่าง)</span>
                                    @elseif($req->status == 'in_progress')
                                        <span style="background: #bfdbfe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">ช่างกำลังดำเนินการ</span>
                                    @elseif($req->status == 'completed')
                                        <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">ซ่อมเสร็จสิ้น</span>
                                    @else
                                        <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">{{ $req->status }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($req->status == 'completed')
                                        <a href="#" style="background: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold; display: inline-block;">
                                            💳 ชำระเงิน
                                        </a>
                                    @else
                                        <span style="color: #9ca3af; font-size: 14px;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($requests->isEmpty())
                            <tr>
                                <td colspan="5" style="padding: 16px; text-align: center; color: #6b7280;">คุณยังไม่มีประวัติการใช้บริการครับ</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>