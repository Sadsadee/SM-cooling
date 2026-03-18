import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, router } from '@inertiajs/react';
// จัดระเบียบ Import ให้ครบและไม่ซ้ำ
import { ArrowLeft, User, Phone, MapPin, Receipt, Wrench, CheckCircle2, Image as ImageIcon, Calendar, Clock } from 'lucide-react';

export default function RequestShow({ job }) {
    // 🧮 คำนวณราคา
    const basePrice = parseFloat(job?.service?.base_price || 0);
    const sparePartsTotal = job?.spare_parts?.reduce((sum, item) => sum + parseFloat(item.total_price || 0), 0) || 0;
    const finalTotal = basePrice + sparePartsTotal;

    // ตรวจสอบสถานะ
    const isApproved = job?.status === 'approved' || job?.status === 'in_progress' || job?.status === 'completed';

    // ฟังก์ชันกดยืนยันยอดเงิน (เวอร์ชันมี Loading)
    const handleConfirmPayment = () => {
        if (confirm('คุณตรวจสอบรูปสลิปและยอดเงินเข้าบัญชีถูกต้องแล้วใช่หรือไม่?')) {
            router.post(`/admin/confirm-payment/${job.id}`, {}, {
                onStart: () => {
                    console.log("กำลังส่งข้อมูล...");
                },
                onSuccess: () => {
                    alert('ยืนยันสำเร็จแล้วครับ');
                }
            });
        }
    };

    return (
        <AdminLayout>
            <Head title={`รายละเอียดงาน #${job?.id || ''}`} />

            <div className="max-w-5xl mx-auto pb-24">

                {/* 🔙 ปุ่มย้อนกลับ & Header */}
                <div className="flex items-center justify-between mb-8">
                    <div className="flex items-center gap-4">
                        <Link href="/admin/requests" className="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all">
                            <ArrowLeft size={20} />
                        </Link>
                        <div>
                            <h1 className="text-2xl font-black text-gray-800 flex items-center gap-3">
                                รายละเอียดงาน <span className="text-blue-600">#{job?.id}</span>
                            </h1>
                            <p className="text-sm font-bold text-gray-400 mt-1">ข้อมูลลูกค้า การเบิกอะไหล่ และหลักฐานการชำระเงิน</p>
                        </div>
                    </div>

                    {/* Badge สถานะงาน */}
                    <div className="bg-white px-5 py-2.5 rounded-full shadow-sm border border-gray-100 font-black text-sm uppercase tracking-widest flex items-center gap-2">
                        สถานะ:
                        {job?.status === 'pending' && <span className="text-orange-500">งานเข้าใหม่</span>}
                        {job?.status === 'awaiting_payment' && <span className="text-rose-500">รอชำระเงิน</span>}
                        {job?.status === 'paid' && <span className="text-emerald-500">แจ้งโอนแล้ว (รอตรวจ)</span>}
                        {job?.status === 'approved' && <span className="text-blue-500">พร้อมส่งช่าง</span>}
                        {job?.status === 'in_progress' && <span className="text-purple-500">กำลังดำเนินการ</span>}
                        {job?.status === 'completed' && <span className="text-green-600">เสร็จสิ้น (ปิดจ๊อบ)</span>}
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {/* 📦 คอลัมน์ซ้าย (ข้อมูลหลัก & สลิป) */}
                    <div className="lg:col-span-7 space-y-8">

                        {/* 👤 ข้อมูลลูกค้า (เพิ่มส่วนวัน-เวลา นัดหมาย) */}
                        <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                            <h3 className="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                                <User className="text-blue-500" size={20} /> ข้อมูลผู้แจ้งซ่อม
                            </h3>
                            <div className="bg-gray-50/50 p-6 rounded-2xl space-y-5 border border-gray-50">
                                <div className="flex items-center gap-4">
                                    <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><User size={18} /></div>
                                    <div>
                                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">ชื่อลูกค้า</p>
                                        <p className="font-black text-gray-800 text-lg">{job?.customer?.name}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <div className="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0"><Phone size={18} /></div>
                                    <div>
                                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">เบอร์ติดต่อ</p>
                                        <a href={`tel:${job?.customer?.phone}`} className="font-black text-blue-600 text-base hover:underline">{job?.customer?.phone || '-'}</a>
                                    </div>
                                </div>
                                <div className="flex items-start gap-4">
                                    <div className="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 shrink-0"><MapPin size={18} /></div>
                                    <div>
                                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">สถานที่ปฏิบัติงาน</p>
                                        <p className="text-sm font-bold text-gray-600 leading-relaxed max-w-sm">
                                            {job?.customer?.address_detail} จ.{job?.customer?.province}
                                        </p>
                                    </div>
                                </div>

                                {/* ✨ ส่วนที่เพิ่มใหม่: วันและเวลานัดหมาย (สีส้ม) ✨ */}
                                <div className="flex items-start gap-4 pt-5 mt-3 border-t border-gray-200/60">
                                    <div className="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 shrink-0">
                                        <Calendar size={18} />
                                    </div>
                                    <div>
                                        <p className="text-[11px] font-black text-orange-500 uppercase tracking-widest mb-1">วัน-เวลานัดหมาย (สำหรับโทรคอนเฟิร์ม)</p>
                                        <p className="text-base font-black text-orange-600 leading-relaxed">
                                            {job?.appointment_date ? new Date(job.appointment_date).toLocaleDateString('th-TH', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            }) : 'ไม่ระบุวันที่'}

                                            <span className="inline-flex items-center gap-1.5 ml-3 text-blue-600 bg-blue-100 px-2 py-0.5 rounded-md text-sm">
                                                <Clock size={14} /> {job?.appointment_time ? `${job.appointment_time} น.` : 'ไม่ระบุเวลา'}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>


                        {/* หลักฐานการโอนเงิน (สลิป) */}
                        <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-lg font-black text-gray-800 flex items-center gap-2">
                                    <ImageIcon className="text-emerald-500" size={20} /> หลักฐานการโอนเงิน
                                </h3>
                                {isApproved && <span className="bg-emerald-100 text-emerald-700 text-xs font-black px-3 py-1.5 rounded-full flex items-center gap-1"><CheckCircle2 size={14} /> ตรวจสอบแล้ว</span>}
                            </div>

                            <div className="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-4 flex flex-col items-center justify-center min-h-[300px] relative overflow-hidden group">
                                {job?.slip_filename ? (
                                    <>
                                        <a
                                            href={`/slips/${job.slip_filename}`}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="block w-full text-center"
                                        >
                                            <img
                                                src={`/slips/${job.slip_filename}`}
                                                alt="Payment Slip"
                                                className="max-h-[400px] object-contain rounded-xl mx-auto shadow-sm group-hover:scale-105 transition-transform duration-300"
                                                onError={(e) => {
                                                    if (!e.target.src.includes('placehold.co')) {
                                                        e.target.src = `https://placehold.co/400x500?text=ไม่พบไฟล์ใน/public/slips/%0A${job.slip_filename}`;
                                                    }
                                                }}
                                            />
                                        </a>

                                        <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none rounded-xl m-4">
                                            <span className="text-white font-bold bg-black/50 px-4 py-2 rounded-lg backdrop-blur-sm">คลิกเพื่อดูรูปเต็ม</span>
                                        </div>
                                    </>
                                ) : (
                                    <div className="text-center text-gray-400 font-bold flex flex-col items-center gap-3">
                                        <Clock size={40} className="opacity-30" />
                                        <p>ลูกค้ายังไม่ได้แนบสลิปชำระเงิน</p>
                                    </div>
                                )}
                            </div>

                            {/* 🔘 ปุ่มกดยืนยันยอดเงิน (เวอร์ชันบังคับสีเขียว) */}
                            {job?.status === 'paid' && (
                                <div className="mt-8">
                                    <button
                                        onClick={handleConfirmPayment}
                                        style={{
                                            background: '#10b981', // สีเขียว Emerald 500
                                            color: '#ffffff',
                                            width: '100%',
                                            padding: '20px',
                                            borderRadius: '16px',
                                            fontSize: '20px',
                                            fontWeight: '900',
                                            border: 'none',
                                            cursor: 'pointer',
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            gap: '12px',
                                            boxShadow: '0 10px 15px -3px rgba(16, 185, 129, 0.2)'
                                        }}
                                        onMouseOver={(e) => e.currentTarget.style.background = '#059669'}
                                        onMouseOut={(e) => e.currentTarget.style.background = '#10b981'}
                                    >
                                        <CheckCircle2 size={24} /> ยืนยันยอดเงินเข้าบัญชีเรียบร้อย
                                    </button>
                                    <p className="text-center text-xs text-emerald-600 font-bold mt-4 animate-pulse" style={{ color: '#059669' }}>
                                        * โปรดตรวจสอบสลิปและยอดเงินก่อนกดยืนยัน
                                    </p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* 🧾 คอลัมน์ขวา (บิลสรุปยอด & รายการอะไหล่) */}
                    <div className="lg:col-span-5 space-y-8">

                        {/* บิลสรุปยอด (Dark Mode) */}
                        <div className="bg-gray-900 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                            <Receipt className="w-48 h-48 absolute -right-10 -top-10 text-white opacity-5" />
                            <h3 className="text-xl font-black mb-6 flex items-center gap-2 relative z-10">
                                <Receipt className="text-blue-400" size={24} /> สรุปยอดเรียกเก็บ
                            </h3>

                            <div className="space-y-4 border-b border-white/10 pb-6 mb-6 text-sm font-bold opacity-90 relative z-10">
                                <div>
                                    <p className="text-[10px] uppercase tracking-widest text-blue-400 mb-1">บริการหลัก</p>
                                    <div className="flex justify-between items-center text-base">
                                        <span>{job?.service?.service_name || 'บริการทั่วไป'}</span>
                                        <span>฿{basePrice.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                                    </div>
                                </div>
                                <div className="pt-2">
                                    <p className="text-[10px] uppercase tracking-widest text-blue-400 mb-1">รวมค่าวัสดุ / อะไหล่</p>
                                    <div className="flex justify-between items-center text-base">
                                        <span>เบิกอะไหล่ {job?.spare_parts?.length || 0} รายการ</span>
                                        <span>฿{sparePartsTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-between items-end relative z-10">
                                <p className="text-xs font-black uppercase tracking-widest text-blue-400">ยอดรวมสุทธิ</p>
                                <p className="text-4xl font-black text-emerald-400 tracking-tight">฿{finalTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</p>
                            </div>
                        </div>

                        {/* รายการเบิกอะไหล่ */}
                        <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="font-black text-lg text-gray-800 flex items-center gap-2">
                                    <Wrench className="text-orange-500" size={20} /> รายการเบิกอะไหล่
                                </h3>
                                <span className="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    {job?.spare_parts?.length || 0} รายการ
                                </span>
                            </div>

                            <div className="space-y-3">
                                {job?.spare_parts?.length > 0 ? (
                                    job.spare_parts.map((item, idx) => (
                                        <div key={idx} className="flex justify-between items-center bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                                            <div className="flex flex-col">
                                                <span className="font-black text-gray-700 text-sm">{item.spare_part?.part_name || 'ไม่พบชื่ออะไหล่'}</span>
                                                <span className="text-xs text-gray-400 font-bold mt-0.5">
                                                    ฿{parseFloat(item.price_at_time || (item.total_price / item.quantity)).toLocaleString()} / ชิ้น
                                                </span>
                                            </div>
                                            <div className="text-right">
                                                <span className="font-black text-blue-600 bg-blue-100 px-3 py-1 rounded-lg text-sm mb-1 inline-block">
                                                    x{item.quantity}
                                                </span>
                                                <p className="text-xs font-black text-gray-800">฿{parseFloat(item.total_price || 0).toLocaleString()}</p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="text-center py-8 text-gray-400 font-bold text-sm bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                        ไม่มีการเบิกอะไหล่ในงานนี้
                                    </div>
                                )}
                            </div>

                            {/* แสดงช่างผู้รับผิดชอบ */}
                            {job?.tech && (
                                <div className="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">ช่างผู้รับผิดชอบ</p>
                                        <p className="font-black text-gray-800 flex items-center gap-2">
                                            <span className="w-2 h-2 rounded-full bg-blue-500"></span> {job.tech.name}
                                        </p>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}