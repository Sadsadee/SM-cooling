import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { Wind, Wrench, ArrowLeft, CalendarDays, Clock, MapPin, PhoneCall, CheckCircle2, AlertCircle, User, Home } from 'lucide-react';

export default function CreateRequest({ services = [] }) {
    
    // フォームの状態管理 (เพิ่ม name, phone, address สำหรับ Guest)
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        phone: '',
        address_detail: '',
        service_id: '',
        problem_details: '',
        appointment_date: new Date().toISOString().split('T')[0], // วันนี้
        appointment_time: '10:00',
    });

    const [submitted, setSubmitted] = useState(false);

    // ส่งฟอร์มไปที่ Route Public
    const handleSubmit = (e) => {
        e.preventDefault();
        post('/request-service', {
            onSuccess: () => {
                setSubmitted(true);
                reset(); 
                setTimeout(() => setSubmitted(false), 5000);
            },
        });
    };

    const selectedService = services.find(s => s.id === parseInt(data.service_id));

    return (
        <div style={{ fontFamily: "'Sora', sans-serif", background: '#f8fafc', minHeight: '100vh' }}>
            <Head title="แจ้งซ่อม/ล้างแอร์ - SM Cooling" />

            {/* 🧭 Navbar เรียบหรู */}
            <nav className="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
                <div className="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
                    <Link href="/" className="flex items-center gap-3 group">
                        <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 group-hover:text-blue-600 group-hover:bg-blue-50 transition-all">
                            <ArrowLeft size={20} />
                        </div>
                        <span className="text-xl font-black text-gray-800 tracking-tight">ย้อนกลับหน้าแรก</span>
                    </Link>
                    <div className="flex items-center gap-2">
                        <div className="text-right hidden sm:block">
                            <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-0.5">ยินดีต้อนรับสู่</p>
                            <p className="text-sm font-black text-gray-800">SM Cooling Center</p>
                        </div>
                    </div>
                </div>
            </nav>

            <main className="max-w-6xl mx-auto px-6 py-10">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    
                    {/* 📦 ส่วนที่ 1: แบบฟอร์มแจ้งซ่อม (Left) */}
                    <div className="lg:col-span-7 space-y-8">
                        <h1 className="text-3xl font-black text-gray-800 flex items-center gap-3">
                            <Wrench className="text-blue-600" /> แจ้งซ่อมแอร์/ล้างแอร์
                        </h1>

                        {submitted && (
                            <div className="bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-3 text-emerald-700 font-bold text-sm animate-in fade-in zoom-in duration-300">
                                <CheckCircle2 className="shrink-0" size={20} />
                                ส่งข้อมูลสำเร็จ! ระบบจะแสดงรหัสติดตามงานให้คุณในหน้าถัดไป
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-lg shadow-gray-100 border border-gray-100 space-y-10">
                            
                            {/* ส่วนที่ 1: ข้อมูลผู้ติดต่อ (สำคัญสำหรับ Guest) */}
                            <section className="space-y-6">
                                <h3 className="text-xl font-black text-gray-800 flex items-center gap-2">
                                    <span className="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">1</span>
                                    ข้อมูลผู้ติดต่อ
                                </h3>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-gray-500 mb-1 flex items-center gap-2">
                                            <User size={16} className="text-blue-500" /> ชื่อ-นามสกุล:
                                        </label>
                                        <input 
                                            type="text" 
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            placeholder="ระบุชื่อของคุณ"
                                            className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                        />
                                        {errors.name && <p className="text-red-500 text-xs mt-1 font-bold">{errors.name}</p>}
                                    </div>
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-gray-500 mb-1 flex items-center gap-2">
                                            <PhoneCall size={16} className="text-blue-500" /> เบอร์โทรศัพท์:
                                        </label>
                                        <input 
                                            type="tel" 
                                            value={data.phone}
                                            onChange={(e) => setData('phone', e.target.value)}
                                            placeholder="08x-xxx-xxxx"
                                            className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                        />
                                        {errors.phone && <p className="text-red-500 text-xs mt-1 font-bold">{errors.phone}</p>}
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <label className="block text-sm font-bold text-gray-500 mb-1 flex items-center gap-2">
                                        <Home size={16} className="text-blue-500" /> ที่อยู่สำหรับการเดินทาง:
                                    </label>
                                    <textarea 
                                        value={data.address_detail}
                                        onChange={(e) => setData('address_detail', e.target.value)}
                                        placeholder="ระบุบ้านเลขที่ / หมู่บ้าน / ซอย / จุดสังเกต"
                                        rows="3"
                                        className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                    ></textarea>
                                    {errors.address_detail && <p className="text-red-500 text-xs mt-1 font-bold">{errors.address_detail}</p>}
                                </div>
                            </section>

                            {/* ส่วนที่ 2: เลือกบริการ */}
                            <section className="space-y-6">
                                <h3 className="text-xl font-black text-gray-800 flex items-center gap-2">
                                    <span className="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">2</span>
                                    เลือกบริการที่ต้องการ
                                </h3>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {services.map((service) => (
                                        <label 
                                            key={service.id} 
                                            className={`relative bg-gray-50 p-6 rounded-3xl border-2 transition-all cursor-pointer block group ${data.service_id === service.id.toString() ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-blue-200'}`}
                                        >
                                            <input 
                                                type="radio" 
                                                name="service_id" 
                                                value={service.id} 
                                                checked={data.service_id === service.id.toString()}
                                                onChange={(e) => setData('service_id', e.target.value)} 
                                                className="absolute top-4 right-4 text-blue-600 focus:ring-blue-500 w-5 h-5"
                                            />
                                            <div className={`w-12 h-12 rounded-2xl flex items-center justify-center mb-4 transition-colors ${data.service_id === service.id.toString() ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white'}`}>
                                                {service.service_name.includes('ล้าง') ? <Wind size={24} /> : <Wrench size={24} />}
                                            </div>
                                            <h4 className="text-base font-black text-gray-800 mb-1">{service.service_name}</h4>
                                            <p className="text-xs text-gray-400 font-bold mb-4">เริ่มต้นเพียง ฿{parseFloat(service.base_price).toLocaleString()}</p>
                                        </label>
                                    ))}
                                </div>
                                {errors.service_id && <p className="text-red-500 text-xs mt-2 font-bold">{errors.service_id}</p>}
                            </section>

                            {/* ส่วนที่ 3: รายละเอียดปัญหาและวันเวลานัดหมาย */}
                            <section className="space-y-6">
                                <h3 className="text-xl font-black text-gray-800 flex items-center gap-2">
                                    <span className="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">3</span>
                                    รายละเอียดปัญหาและวันเวลานัดหมาย
                                </h3>
                                <div className="space-y-4">
                                    <textarea 
                                        value={data.problem_details}
                                        onChange={(e) => setData('problem_details', e.target.value)}
                                        rows="3" 
                                        placeholder="ระบุอาการเบื้องต้น เช่น แอร์ไม่เย็น, มีน้ำหยด, ฯลฯ" 
                                        className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                    ></textarea>
                                </div>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-gray-500 mb-1.5 flex items-center gap-2">
                                            <CalendarDays size={16} className="text-blue-500" /> วันที่นัดหมาย:
                                        </label>
                                        <input 
                                            type="date" 
                                            value={data.appointment_date}
                                            onChange={(e) => setData('appointment_date', e.target.value)}
                                            min={new Date().toISOString().split('T')[0]}
                                            className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-gray-500 mb-1.5 flex items-center gap-2">
                                            <Clock size={16} className="text-blue-500" /> เวลานัดหมาย:
                                        </label>
                                        <input 
                                            type="time" 
                                            value={data.appointment_time}
                                            onChange={(e) => setData('appointment_time', e.target.value)}
                                            className="w-full bg-gray-50/50 p-4 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-200 font-medium"
                                        />
                                    </div>
                                </div>
                                <p className="text-xs text-orange-600 font-bold bg-orange-50 p-4 rounded-xl border border-orange-100 flex items-center gap-2">
                                    <AlertCircle size={16} /> 
                                    * พนักงานจะโทรยืนยันคิวงานอีกครั้งหลังจากได้รับข้อมูล
                                </p>
                            </section>

                            {/* ปุ่มส่งฟอร์ม */}
                            <div className="pt-8">
                                <button 
                                    type="submit" 
                                    disabled={processing}
                                    className={`w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl text-xl shadow-xl shadow-blue-200 transition-all ${processing ? 'opacity-50 cursor-not-allowed' : 'active:scale-95'}`}
                                >
                                    {processing ? 'กำลังส่งข้อมูล...' : 'ยืนยันการจองบริการ'}
                                </button>
                            </div>

                        </form>
                    </div>

                    {/* 🧾 ส่วนที่ 2: สรุปข้อมูล (Right - Sticky) */}
                    <div className="lg:col-span-5 space-y-8 sticky top-28 h-fit">
                        <div className="bg-gray-900 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                            <Wind className="w-48 h-48 absolute -right-10 -top-10 text-white opacity-5" />
                            <h3 className="text-xl font-black mb-6 flex items-center gap-2 relative z-10 text-blue-400">
                                <span className="w-8 h-8 rounded-full bg-blue-400 text-white flex items-center justify-center font-bold text-sm">🧾</span>
                                สรุปข้อมูลการจอง
                            </h3>
                            
                            {selectedService ? (
                                <div className="space-y-6 relative z-10">
                                    <div className="bg-white/10 p-5 rounded-2xl">
                                        <p className="text-xs font-bold text-blue-300 mb-1 uppercase tracking-widest">บริการที่เลือก:</p>
                                        <p className="text-lg font-black text-white leading-tight">{selectedService.service_name}</p>
                                    </div>
                                    <div className="grid grid-cols-2 gap-4 text-sm font-medium">
                                        <div>
                                            <p className="text-xs text-blue-300 mb-1">📅 วันที่:</p>
                                            <p className="font-black text-white">{new Date(data.appointment_date).toLocaleDateString('th-TH')}</p>
                                        </div>
                                        <div>
                                            <p className="text-xs text-blue-300 mb-1">⏰ เวลา:</p>
                                            <p className="font-black text-white">{data.appointment_time} น.</p>
                                        </div>
                                    </div>
                                    <div className="pt-6 border-t border-white/10 flex justify-between items-end">
                                        <p className="text-xs font-black uppercase tracking-widest text-blue-300">ยอดชำระเริ่มต้น</p>
                                        <p className="text-4xl font-black text-emerald-400 tracking-tight">฿{parseFloat(selectedService.base_price).toLocaleString()}</p>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center py-16 text-gray-500 font-bold border-2 border-dashed border-gray-700 rounded-2xl relative z-10">
                                    <AlertCircle size={40} className="mx-auto opacity-30 mb-3" />
                                    กรุณาเลือกบริการ <br /> เพื่อดูสรุปยอดเงิน
                                </div>
                            )}
                        </div>

                        <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-5">
                            <h3 className="text-lg font-black text-gray-800 flex items-center gap-2">
                                <PhoneCall className="text-orange-500" size={20} /> สอบถามข้อมูลเพิ่มเติม
                            </h3>
                            <p className="text-orange-600 font-black text-2xl hover:underline">
                                <a href="tel:021234567">02-123-4567</a>
                            </p>
                            <p className="text-xs font-bold text-gray-400 leading-relaxed">
                                เปิดให้บริการทุกวัน <br /> 08:30 น. - 18:00 น.
                            </p>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    );
}