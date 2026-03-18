import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Clock, CheckCircle2, Wrench, Receipt, ImageIcon, AlertCircle, Phone, MapPin } from 'lucide-react';

export default function ShowStatus({ auth, job }) {

    // ฟอร์มสำหรับอัปโหลดสลิป
    const { data, setData, post, processing, errors } = useForm({
        slip_image: null,
    });

    const handleUpload = (e) => {
        e.preventDefault();
        post(`/customer/requests/${job.id}/upload-slip`);
    };

    // 🧮 ระบบคำนวณราคา & หักมัดจำ (เวอร์ชันกันเกรียน)
    const basePrice = parseFloat(job.service?.base_price || 0);
    const sparePartsTotal = job.spare_parts?.reduce((sum, item) => sum + parseFloat(item.total_price || 0), 0) || 0;
    const grandTotal = basePrice + sparePartsTotal;

    // ✨ เช็คว่าแอดมินกดยืนยันยอดหรือยัง (สถานะต้องเลยคำว่า 'paid' ไปแล้ว เช่น approved, in_progress, completed)
    const isPaymentVerified = ['approved', 'in_progress', 'completed'].includes(job.status);

    // ✨ หักมัดจำก็ต่อเมื่อแอดมินยืนยันแล้วเท่านั้น!
    const depositAmount = isPaymentVerified ? basePrice : 0;
    const remainingBalance = grandTotal - depositAmount;

    return (
        <div style={{ fontFamily: "'Sora', sans-serif", background: '#f8fafc', minHeight: '100vh' }} className="pb-20">
            <Head title={`ติดตามงาน #${job.id}`} />

            {/* Navbar */}
            <nav className="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
                <div className="max-w-5xl mx-auto px-6 h-20 flex items-center gap-4">
                    <Link href="/" className="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-all">
                        <ArrowLeft size={20} />
                    </Link>
                    <h1 className="text-xl font-black text-gray-800">รายละเอียดงาน #{job.id}</h1>
                </div>
            </nav>

            <main className="max-w-5xl mx-auto px-6 mt-8 space-y-6">

                {/* 🏷️ การ์ดสถานะงาน */}
                <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 text-center">
                    <div className="inline-flex w-16 h-16 rounded-full bg-blue-50 items-center justify-center text-blue-600 mb-4">
                        <Clock size={32} />
                    </div>
                    <h2 className="text-2xl font-black text-gray-800 mb-2">
                        {job.status === 'pending' && 'กำลังรอแอดมินรับเรื่อง'}
                        {job.status === 'awaiting_payment' && 'รอชำระเงิน'}
                        {job.status === 'paid' && 'แจ้งโอนแล้ว รอตรวจสอบ'}
                        {job.status === 'approved' && 'พร้อมส่งช่าง'}
                        {job.status === 'in_progress' && 'ช่างกำลังดำเนินการ'}
                        {job.status === 'completed' && 'ปิดงานเรียบร้อยแล้ว'}
                    </h2>
                    <p className="text-gray-400 font-bold text-sm">
                        อัปเดตล่าสุด: {new Date(job.updated_at).toLocaleString('th-TH')}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {/* 🧾 รายละเอียดค่าใช้จ่าย (แบบใหม่ หักมัดจำได้) */}
                    <div className="bg-[#0f172a] rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden flex flex-col justify-between">
                        {/* พื้นหลังตกแต่ง */}
                        <div className="absolute -right-10 -top-10 w-40 h-40 bg-blue-500 rounded-full mix-blend-multiply filter blur-[50px] opacity-20"></div>

                        <h3 className="text-lg font-black mb-6 flex items-center gap-2 relative z-10">
                            <span className="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-sm">🧾</span>
                            สรุปค่าใช้จ่าย
                        </h3>

                        <div className="space-y-4 relative z-10">
                            <div className="flex justify-between text-slate-300 text-sm font-medium">
                                <span>ค่าบริการหลัก ({job.service?.service_name})</span>
                                <span>฿{basePrice.toLocaleString()}</span>
                            </div>

                            {sparePartsTotal > 0 && (
                                <div className="flex justify-between text-slate-300 text-sm font-medium">
                                    <span>ค่าอะไหล่ ({job.spare_parts?.length || 0} รายการ)</span>
                                    <span>฿{sparePartsTotal.toLocaleString()}</span>
                                </div>
                            )}

                            <div className="border-t border-slate-700/50 my-4"></div>

                            <div className="flex justify-between font-bold text-white text-sm">
                                <span>ยอดรวมทั้งสิ้น</span>
                                <span>฿{grandTotal.toLocaleString()}</span>
                            </div>

                            {/* ✨ โชว์รายการหักมัดจำ (แบบ 2 สถานะ) */}
                            {job.slip_filename && !isPaymentVerified && (
                                <div className="flex justify-between items-center text-orange-400 text-sm font-bold mt-2 bg-orange-400/10 p-3 rounded-xl border border-orange-400/20 animate-pulse">
                                    <span className="flex items-center gap-2"><Clock size={14} /> แจ้งโอนแล้ว (รอตรวจสอบ)</span>
                                    <span>รอหักยอด</span>
                                </div>
                            )}
                            {isPaymentVerified && depositAmount > 0 && (
                                <div className="flex justify-between items-center text-emerald-400 text-sm font-bold mt-2 bg-emerald-400/10 p-3 rounded-xl border border-emerald-400/20">
                                    <span className="flex items-center gap-2"><CheckCircle2 size={14} /> หักมัดจำ (แอดมินยืนยันแล้ว)</span>
                                    <span>- ฿{depositAmount.toLocaleString()}</span>
                                </div>
                            )}

                            <div className="border-t border-slate-700/50 my-4"></div>

                            {/* ยอดคงเหลือชำระหน้างาน */}
                            <div className="flex justify-between items-end pt-2">
                                <div className="space-y-1">
                                    <span className="block text-sm font-bold text-slate-300 uppercase tracking-wider">
                                        ยอดชำระช่างหน้างาน
                                    </span>
                                    {remainingBalance <= 0 && job.status === 'completed' && (
                                        <span className="text-[10px] font-black text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-md border border-emerald-400/20">
                                            ชำระครบแล้ว
                                        </span>
                                    )}
                                </div>
                                <span className={`text-4xl font-black tracking-tight ${remainingBalance > 0 ? 'text-blue-400' : 'text-emerald-400'}`}>
                                    ฿{remainingBalance > 0 ? remainingBalance.toLocaleString() : '0'}
                                </span>
                            </div>
                        </div>
                    </div>

                    {/* 📸 ส่วนการชำระเงิน / อัปโหลดสลิป */}
                    <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 flex flex-col justify-center">
                        <h3 className="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                            <ImageIcon className="text-blue-600" size={20} /> หลักฐานการโอนมัดจำ
                        </h3>

                        {job.slip_filename ? (
                            <div className="space-y-4 text-center">
                                <div className={`p-4 rounded-2xl text-xs font-black flex items-center justify-center gap-2 border ${isPaymentVerified ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-orange-50 text-orange-600 border-orange-100'}`}>
                                    {isPaymentVerified ? (
                                        <><CheckCircle2 size={16} /> ยืนยันสลิปถูกต้องแล้ว</>
                                    ) : (
                                        <><Clock size={16} className="animate-spin-slow" /> กำลังรอแอดมินตรวจสอบสลิป</>
                                    )}
                                </div>
                                <img
                                    src={`/slips/${job.slip_filename}`}
                                    className="max-h-48 mx-auto rounded-xl shadow-sm border border-gray-100"
                                    alt="Slip"
                                />
                            </div>
                        ) : (
                            job.status === 'awaiting_payment' ? (
                                <form onSubmit={handleUpload} className="space-y-4">
                                    <div className="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex items-start gap-3">
                                        <AlertCircle className="text-blue-600 shrink-0 mt-0.5" size={18} />
                                        <p className="text-xs font-bold text-blue-700 leading-relaxed">
                                            ยอดโอนมัดจำ: <span className="text-lg text-blue-900 font-black">฿{basePrice.toLocaleString()}</span> <br />
                                            ธ.กสิกรไทย 000-0-00000-0 <br />
                                            บจก. เอสเอ็ม คูลลิ่ง เซ็นเตอร์
                                        </p>
                                    </div>
                                    <div className="relative">
                                        <input
                                            type="file"
                                            onChange={e => setData('slip_image', e.target.files[0])}
                                            className="text-xs font-bold block w-full file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer bg-gray-50 rounded-xl border border-dashed border-gray-200 p-2"
                                        />
                                    </div>
                                    {errors.slip_image && <p className="text-red-500 text-[10px] font-bold">{errors.slip_image}</p>}
                                    <button
                                        disabled={processing}
                                        className="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-100 active:scale-95 transition-all"
                                    >
                                        {processing ? 'กำลังอัปโหลด...' : 'ยืนยันแจ้งโอนมัดจำ'}
                                    </button>
                                </form>
                            ) : (
                                <div className="text-center py-12 text-gray-400 font-bold text-sm bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                                    <Receipt className="mx-auto mb-3 opacity-50" size={32} />
                                    รอแอดมินสรุปยอดมัดจำ
                                </div>
                            )
                        )}
                    </div>
                </div>

                {/* 👨‍🔧 ข้อมูลช่างผู้ดูแล */}
                <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                    <h3 className="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                        <Wrench className="text-orange-500" size={20} /> ช่างผู้รับผิดชอบงานของคุณ
                    </h3>
                    {job.tech ? (
                        <div className="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div className="flex items-center gap-4">
                                <div className="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-blue-600 border border-blue-50">
                                    <Wrench size={24} />
                                </div>
                                <div>
                                    <p className="font-black text-gray-800 text-lg">{job.tech.name}</p>
                                    <p className="text-sm font-bold text-blue-600 flex items-center gap-1">
                                        <Phone size={14} /> {job.tech.phone || 'ไม่ระบุเบอร์โทร'}
                                    </p>
                                </div>
                            </div>
                            {job.tech.phone && (
                                <a href={`tel:${job.tech.phone}`} className="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-green-200 transition-all active:scale-95">
                                    <Phone size={20} />
                                </a>
                            )}
                        </div>
                    ) : (
                        <div className="text-center py-8 text-gray-400 font-bold text-sm bg-gray-50 rounded-[2rem] border border-dashed border-gray-200">
                            อยู่ระหว่างการจัดหาช่างที่เหมาะสม...
                        </div>
                    )}
                </div>

            </main>
        </div>
    );
}