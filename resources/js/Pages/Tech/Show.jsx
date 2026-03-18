import React, { useState } from 'react';
import TechLayout from '@/Layouts/TechLayout';
import { Head, useForm, Link, router } from '@inertiajs/react';
import { ArrowLeft, User, Phone, MapPin, Receipt, Wrench, Plus, CheckCircle2, X } from 'lucide-react';

const Show = ({ job, inventory }) => {
    const [showModal, setShowModal] = useState(false);

    const { data, setData, post, reset, processing, errors } = useForm({
        spare_part_id: '',
        quantity: 1
    });

    // 🧮 คำนวณราคาและหักมัดจำ
    const basePrice = parseFloat(job?.service?.base_price || 0);
    const sparePartsTotal = job?.spare_parts?.reduce((sum, item) => sum + parseFloat(item.total_price || 0), 0) || 0;
    const grandTotal = basePrice + sparePartsTotal;

    // มัดจำคือค่าบริการหลัก (ถ้ามีสลิป = จ่ายแล้ว)
    const depositAmount = job?.slip_filename ? basePrice : 0;
    const remainingBalance = grandTotal - depositAmount;

    const isCompleted = job?.status === 'completed';

    const submitPart = (e) => {
        e.preventDefault();
        post(`/tech/requests/${job?.id}/add-part`, {
            preserveScroll: true,
            onSuccess: () => {
                setShowModal(false);
                reset();
            }
        });
    };

    const handleCompleteJob = () => {
        if (confirm('ยืนยันการปิดงานและแจ้งยอดลูกค้าใช่หรือไม่?')) {
            router.post(`/tech/requests/${job?.id}/complete`);
        }
    };

    return (
        <>
            <Head title={`งาน #${job?.id || ''}`} />

            <div className={`max-w-3xl mx-auto px-4 py-6 space-y-6 ${!isCompleted ? 'pb-32' : 'pb-10'}`}>

                <Link href="/tech/dashboard" className="inline-flex items-center gap-2 text-sm font-black text-gray-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                    <ArrowLeft className="w-4 h-4" /> กลับหน้าคิวงาน
                </Link>

                {/* 🏷️ ข้อมูลลูกค้า */}
                <div className="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                    <div className="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                        <h2 className="text-gray-400 font-black text-sm uppercase tracking-widest">
                            งาน #{job?.id}
                        </h2>
                        <span className={`px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 ${isCompleted ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600'}`}>
                            {isCompleted ? <CheckCircle2 className="w-3 h-3" /> : <Wrench className="w-3 h-3" />}
                            {isCompleted ? 'เสร็จสิ้นแล้ว' : 'กำลังดำเนินการ'}
                        </span>
                    </div>

                    <h3 className="font-black text-2xl text-gray-800 mb-6 leading-tight">
                        {job?.service?.service_name || 'บริการทั่วไป'}
                    </h3>

                    <div className="space-y-4 bg-gray-50/50 p-5 rounded-2xl">
                        <div className="flex items-center gap-3">
                            <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0"><User className="w-4 h-4 text-blue-500" /></div>
                            <p className="font-bold text-gray-700 text-sm">{job?.customer?.name}</p>
                        </div>
                        <div className="flex items-center gap-3">
                            <div className="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center shrink-0"><Phone className="w-4 h-4 text-green-500" /></div>
                            <a href={`tel:${job?.customer?.phone}`} className="font-black text-blue-600 text-base hover:underline">
                                {job?.customer?.phone || 'ไม่ระบุเบอร์'}
                            </a>
                        </div>
                        <div className="flex items-start gap-3">
                            <div className="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5"><MapPin className="w-4 h-4 text-red-500" /></div>
                            <p className="text-sm font-bold text-gray-600 leading-relaxed">
                                {job?.customer?.address_detail} <br /> จ.{job?.customer?.province}
                            </p>
                        </div>
                    </div>
                </div>

                {/* 🧾 สรุปยอดเก็บเงินหน้างาน (แก้ใหม่ให้ตรงฝั่งลูกค้า) */}
                <div className="bg-[#0f172a] rounded-[2rem] p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                    <div className="absolute -right-10 -bottom-10 opacity-10">
                        <span className="text-[150px] leading-none font-black">$</span>
                    </div>

                    <h3 className="text-lg font-black mb-6 flex items-center gap-2 relative z-10">
                        <Receipt className="w-5 h-5 text-blue-400" /> สรุปยอดเก็บเงินหน้างาน
                    </h3>

                    <div className="space-y-4 relative z-10">
                        <div className="flex justify-between text-slate-300 text-sm font-medium">
                            <span>ค่าบริการพื้นฐาน</span>
                            <span>฿{basePrice.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                        </div>

                        {sparePartsTotal > 0 && (
                            <div className="flex justify-between text-slate-300 text-sm font-medium">
                                <span>รวมค่าวัสดุ/อะไหล่</span>
                                <span>฿{sparePartsTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                            </div>
                        )}

                        <div className="flex justify-between font-bold text-white text-sm pt-2">
                            <span>ยอดรวมทั้งสิ้น</span>
                            <span>฿{grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                        </div>

                        {/* แสดงการหักมัดจำ (ให้ช่างเห็นชัดๆ) */}
                        {depositAmount > 0 && (
                            <div className="flex justify-between items-center bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20 text-emerald-400 text-sm font-bold mt-2">
                                <span>หักมัดจำ (ลูกค้าโอนแล้ว)</span>
                                <span>- ฿{depositAmount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                            </div>
                        )}

                        <div className="border-t border-slate-700/50 my-4"></div>

                        {/* ยอดคงเหลือที่ช่างต้องเก็บ */}
                        <div className="flex justify-between items-end">
                            <span className="text-sm font-bold text-blue-400 uppercase tracking-wider">
                                ยอดที่ต้องเก็บเพิ่ม
                            </span>
                            <span className={`text-4xl font-black tracking-tight ${remainingBalance > 0 ? 'text-white' : 'text-emerald-400'}`}>
                                ฿{remainingBalance > 0 ? remainingBalance.toLocaleString(undefined, { minimumFractionDigits: 2 }) : '0.00'}
                            </span>
                        </div>
                    </div>
                </div>

                {/* 🔧 เบิกอะไหล่ */}
                <div className="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                    <div className="flex justify-between items-center mb-5">
                        <h3 className="font-black text-lg text-gray-800 flex items-center gap-2">
                            <Wrench className="w-5 h-5 text-gray-400" /> วัสดุ/อะไหล่ที่ใช้
                        </h3>
                        <span className="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">
                            {job?.spare_parts?.length || 0} รายการ
                        </span>
                    </div>

                    <div className="space-y-3">
                        {job?.spare_parts?.map((item, idx) => (
                            <div key={idx} className="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div className="flex flex-col">
                                    <span className="font-black text-gray-700 text-sm">{item.spare_part?.part_name || 'ไม่พบอะไหล่'}</span>
                                    <span className="text-xs text-gray-400 font-bold mt-0.5">
                                        ฿{parseFloat(item.price_at_time || (item.total_price / item.quantity)).toLocaleString(undefined, { minimumFractionDigits: 2 })} / ชิ้น
                                    </span>
                                </div>
                                <span className="font-black text-blue-600 bg-blue-100 px-3 py-1.5 rounded-lg text-sm">
                                    x{item.quantity}
                                </span>
                            </div>
                        ))}

                        {!isCompleted && (
                            <button
                                onClick={() => setShowModal(true)}
                                className="w-full py-4 mt-2 border-2 border-dashed border-gray-200 rounded-xl font-black text-gray-400 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition-all flex justify-center items-center gap-2"
                            >
                                <Plus className="w-5 h-5" /> เบิกอะไหล่ / วัสดุเพิ่ม
                            </button>
                        )}
                    </div>
                </div>
            </div>

            {/* 🟢 ปุ่มปิดงานด้านล่างสุด */}
            {!isCompleted && (
                <div className="fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-md border-t border-gray-100 p-4 pb-safe z-40 flex justify-center shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                    <div className="w-full max-w-3xl">
                        <button
                            onClick={handleCompleteJob}
                            className={`w-full text-white font-black py-4 rounded-2xl text-lg shadow-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 ${remainingBalance > 0 ? 'bg-[#0f172a] hover:bg-black' : 'bg-emerald-600 hover:bg-emerald-700'}`}
                        >
                            <CheckCircle2 className="w-5 h-5" />
                            {remainingBalance > 0
                                ? `เก็บเงิน ฿${remainingBalance.toLocaleString(undefined, { minimumFractionDigits: 2 })} และปิดงาน`
                                : 'ลูกค้าชำระครบแล้ว - ปิดงาน'
                            }
                        </button>
                    </div>
                </div>
            )}

            {/* ➕ Modal เบิกอะไหล่ */}
            {showModal && !isCompleted && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-end sm:items-center justify-center p-4">
                    <div className="bg-white w-full max-w-md rounded-[2rem] p-6 sm:p-8 animate-slide-up">
                        <div className="flex justify-between items-center mb-6">
                            <h3 className="text-xl font-black text-gray-800">เบิกอะไหล่ลงงาน</h3>
                            <button onClick={() => setShowModal(false)} className="text-gray-300 hover:text-gray-500 transition-colors">
                                <X className="w-6 h-6" />
                            </button>
                        </div>

                        <form onSubmit={submitPart} className="space-y-5">
                            <div>
                                <label className="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">เลือกจากคลัง</label>
                                <select className="w-full p-4 rounded-xl bg-gray-50 font-bold border border-gray-100 focus:ring-2 focus:ring-blue-500 outline-none appearance-none"
                                    value={data.spare_part_id} onChange={e => setData('spare_part_id', e.target.value)} required>
                                    <option value="">-- แตะเพื่อเลือก --</option>
                                    {inventory?.map(item => (
                                        <option key={item.id} value={item.id}>{item.part_name} (เหลือ {item.stock})</option>
                                    ))}
                                </select>
                                {errors.spare_part_id && <p className="text-red-500 text-xs font-bold mt-1.5 ml-1">{errors.spare_part_id}</p>}
                            </div>

                            <div>
                                <label className="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">จำนวนที่ใช้</label>
                                <input type="number" className="w-full p-4 rounded-xl bg-gray-50 font-bold border border-gray-100 focus:ring-2 focus:ring-blue-500 outline-none"
                                    value={data.quantity} onChange={e => setData('quantity', e.target.value)} min="1" required />
                                {errors.quantity && <p className="text-red-500 text-xs font-bold mt-1.5 ml-1">{errors.quantity}</p>}
                            </div>

                            <div className="flex gap-3 mt-8">
                                <button type="button" onClick={() => setShowModal(false)} className="flex-1 py-4 font-black text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">ยกเลิก</button>
                                <button type="submit" disabled={processing} className="flex-[2] py-4 bg-blue-600 text-white rounded-xl font-black shadow-lg shadow-blue-200 disabled:opacity-50 transition-all flex justify-center items-center gap-2">
                                    {processing ? 'กำลังบันทึก...' : 'ยืนยันเบิกอะไหล่'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
};

Show.layout = page => <TechLayout children={page} />;
export default Show;