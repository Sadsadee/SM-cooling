import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Wind, Wrench, ChevronRight, Search, PhoneCall, CheckCircle2, User, ArrowRight, ShieldCheck, MessageCircle } from 'lucide-react';

export default function UserDashboard({ activeRequests = [], services = [] }) {
    return (
        <div className="min-h-screen bg-slate-50 font-['Sora'] selection:bg-blue-200 relative pb-10">
            <Head title="หน้าแรก - SM Cooling Center" />

            {/* 🧭 Navbar */}
            <nav className="bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                    <Link href="/" className="flex items-center gap-3 group">
                        <div className="w-11 h-11 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200 transform group-hover:rotate-12 transition-all duration-300">
                            <Wind size={24} strokeWidth={2.5} />
                        </div>
                        <span className="text-2xl font-black text-slate-800 tracking-tight">SM Cooling</span>
                    </Link>

                    <div className="flex items-center gap-6">
                        <div className="h-8 w-px bg-slate-200 hidden sm:block"></div>
                        <Link
                            href="/login"
                            className="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 rounded-full font-bold text-sm transition-all border border-slate-200 hover:border-blue-200"
                        >
                            <User size={16} /> <span className="hidden sm:inline">พนักงาน</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <main className="max-w-7xl mx-auto px-6 py-10 space-y-10">

                {/* 🚀 Premium Hero Section */}
                <section className="relative bg-[#0f172a] rounded-[3rem] p-10 lg:p-16 text-white shadow-2xl shadow-slate-900/20 overflow-hidden isolate">
                    <div className="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-[100px] opacity-40 animate-pulse"></div>
                    <div className="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-500 rounded-full mix-blend-screen filter blur-[100px] opacity-20"></div>

                    <div className="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                        <div className="space-y-6">
                            <div className="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 px-4 py-2 rounded-full text-blue-300 font-bold text-xs tracking-widest uppercase">
                                <ShieldCheck size={16} /> ประเมินหน้างานฟรี
                            </div>
                            <h1 className="text-4xl lg:text-6xl font-black leading-[1.15] tracking-tight text-white">
                                เรียกช่างมืออาชีพ <br />
                                <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">
                                    รวดเร็ว ทันใจ
                                </span>
                            </h1>
                            <p className="text-slate-300 text-lg max-w-lg font-medium leading-relaxed">
                                บริการ ล้าง ซ่อม ย้าย ติดตั้ง เครื่องปรับอากาศทุกชนิด ประเมินราคาฟรีหน้างาน นัดหมายง่ายไม่ต้องรอนาน
                            </p>
                            <div className="pt-2">
                                <Link
                                    href="/request-service"
                                    className="inline-flex items-center justify-center gap-3 bg-white text-[#0f172a] font-black px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:bg-gray-50 transition-all active:scale-95 text-lg"
                                >
                                    <Wrench size={22} /> จองคิวช่างเลย
                                </Link>
                            </div>
                        </div>

                        <div className="hidden lg:flex justify-end">
                            <div className="bg-white/5 backdrop-blur-md border border-white/10 p-8 rounded-[2rem] w-full max-w-sm space-y-6">
                                <div className="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 mb-2">
                                    <Search size={28} />
                                </div>
                                <div>
                                    <h3 className="text-2xl font-black text-white mb-2">แจ้งซ่อมไว้แล้ว?</h3>
                                    <p className="text-slate-400 font-medium text-sm leading-relaxed">กรอกเบอร์โทรศัพท์เพื่อติดตามสถานะช่าง และดูรูปหลักฐานการทำงาน</p>
                                </div>
                                <Link href="/track-status" className="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl transition-all group">
                                    ติดตามสถานะงาน <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                    {/* 🛠️ บริการยอดนิยม (ซ้าย) */}
                    <div className="lg:col-span-8 space-y-8">
                        <div className="flex items-center justify-between">
                            <h2 className="text-2xl font-black text-slate-800">
                                บริการยอดนิยมของเรา
                            </h2>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {services.map((service, index) => {
                                // ✨ LOGIC สมดุล: ถ้าจำนวนบริการเป็น "เลขคี่" และนี่คือ "ตัวสุดท้าย" ให้มันขยายเต็ม 2 คอลัมน์ไปเลย
                                const isLastOdd = services.length % 2 !== 0 && index === services.length - 1;

                                return (
                                    <Link
                                        href={`/request-service?service_id=${service.id}`}
                                        key={service.id}
                                        className={`group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-[0_8px_30px_-4px_rgba(6,81,237,0.15)] hover:border-blue-200 transition-all duration-300 relative overflow-hidden flex flex-col justify-between ${isLastOdd ? 'sm:col-span-2 sm:flex-row sm:items-center' : ''}`}
                                    >
                                        <div className="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full opacity-50 transition-transform group-hover:scale-110"></div>

                                        <div className={isLastOdd ? 'flex-1' : ''}>
                                            <div className="flex justify-between items-start mb-6 relative z-10">
                                                <div className="w-14 h-14 bg-slate-50 group-hover:bg-blue-600 rounded-2xl flex items-center justify-center text-blue-600 group-hover:text-white transition-colors duration-300">
                                                    {service.service_name.includes('ล้าง') ? <Wind size={26} strokeWidth={2} /> : <Wrench size={26} strokeWidth={2} />}
                                                </div>
                                                {/* ป้ายแนะนำแสดงเฉพาะบางตัว หรือตัวสุดท้าย */}
                                                {(index === 0 || isLastOdd) && (
                                                    <div className="bg-emerald-50 text-emerald-600 font-black text-[10px] uppercase tracking-widest px-3 py-1.5 rounded-full border border-emerald-100">
                                                        แนะนำ
                                                    </div>
                                                )}
                                            </div>

                                            <h3 className="text-xl font-black text-slate-800 mb-2 group-hover:text-blue-700 transition-colors relative z-10">{service.service_name}</h3>
                                            <div className="flex items-end gap-2 mb-6 relative z-10">
                                                <span className="text-sm font-bold text-slate-400">เริ่มต้น</span>
                                                <span className="text-2xl font-black text-blue-600 leading-none">฿{parseFloat(service.base_price).toLocaleString()}</span>
                                            </div>
                                        </div>

                                        <div className={`flex items-center text-sm font-black text-slate-400 group-hover:text-blue-600 transition-colors gap-2 relative z-10 ${isLastOdd ? 'sm:mb-0 sm:pl-6 sm:border-l sm:border-slate-100' : ''}`}>
                                            จองคิวบริการนี้ <ChevronRight size={16} className="group-hover:translate-x-1 transition-transform" />
                                        </div>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>

                    {/* 📋 โซนขวามือ (ขวา) */}
                    <div className="lg:col-span-4 space-y-6">

                        {/* การ์ดติดตามสถานะ (โชว์เฉพาะมือถือ) */}
                        <div className="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-[2rem] border border-blue-100 shadow-sm lg:hidden relative overflow-hidden">
                            <Wind size={100} className="absolute -right-6 -bottom-6 text-blue-600 opacity-5" />
                            <h3 className="text-xl font-black text-slate-800 mb-2">แจ้งซ่อมไว้แล้วใช่ไหม?</h3>
                            <p className="text-sm font-medium text-slate-500 mb-6 relative z-10">กรอกเบอร์โทรศัพท์เพื่อติดตามสถานะช่างของคุณ</p>
                            <Link href="/track-status" className="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl transition-all active:scale-95 relative z-10">
                                <Search size={18} /> ค้นหาสถานะงาน
                            </Link>
                        </div>

                        {/* กล่องติดต่อร้าน */}
                        <div className="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm text-center">
                            <div className="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <PhoneCall size={28} />
                            </div>
                            <p className="text-xs font-black text-orange-500 uppercase tracking-widest mb-2">ต้องการความช่วยเหลือด่วน?</p>
                            <h3 className="text-2xl font-black text-slate-800 mb-2">02-123-4567</h3>
                            <p className="text-sm font-medium text-slate-500">
                                เปิดให้บริการทุกวัน <br /> 08:30 น. - 18:00 น.
                            </p>
                        </div>

                        {/* ทำไมต้องเลือกเรา */}
                        <div className="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-5">
                            <h3 className="text-lg font-black text-slate-800 mb-4">ทำไมต้อง SM Cooling?</h3>
                            <div className="flex items-start gap-4">
                                <div className="mt-1 shrink-0 text-emerald-500"><CheckCircle2 size={20} /></div>
                                <div>
                                    <p className="font-bold text-slate-800 text-sm">ช่างมืออาชีพ</p>
                                    <p className="text-xs font-medium text-slate-500 mt-1">ผ่านการอบรมและมีประสบการณ์สูง</p>
                                </div>
                            </div>
                            <div className="flex items-start gap-4">
                                <div className="mt-1 shrink-0 text-emerald-500"><CheckCircle2 size={20} /></div>
                                <div>
                                    <p className="font-bold text-slate-800 text-sm">ประเมินราคาฟรี</p>
                                    <p className="text-xs font-medium text-slate-500 mt-1">แจ้งราคาก่อนซ่อมจริง ไม่มีบวกเพิ่ม</p>
                                </div>
                            </div>
                            <div className="flex items-start gap-4">
                                <div className="mt-1 shrink-0 text-emerald-500"><CheckCircle2 size={20} /></div>
                                <div>
                                    <p className="font-bold text-slate-800 text-sm">รับประกันงานซ่อม</p>
                                    <p className="text-xs font-medium text-slate-500 mt-1">อุ่นใจด้วยการรับประกันนาน 30 วัน</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

            {/* ✨ Footer แถบด้านล่างสุด */}
            <footer className="max-w-7xl mx-auto px-6 py-8 mt-10 border-t border-slate-200">
                <div className="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                    <p className="text-sm font-bold text-slate-400">
                        &copy; {new Date().getFullYear()} SM Cooling Center. All rights reserved.
                    </p>
                    <div className="flex items-center gap-6 text-sm font-bold text-slate-400">
                        <Link href="/" className="hover:text-blue-600 transition-colors">ข้อตกลงการบริการ</Link>
                        <Link href="/" className="hover:text-blue-600 transition-colors">นโยบายความเป็นส่วนตัว</Link>
                    </div>
                </div>
            </footer>

            {/* ✨ Floater (Floating Action Button) ขวาล่าง */}
            <div className="fixed bottom-6 right-6 z-[100] flex flex-col gap-3">
                {/* ปุ่มทักแชท (จำลอง) */}
                <a
                    href="#"
                    title="ทักแชทสอบถาม"
                    className="w-12 h-12 bg-white text-blue-600 border border-slate-100 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-50 hover:-translate-y-1 transition-all group"
                >
                    <MessageCircle size={22} className="group-hover:scale-110 transition-transform" />
                </a>
                {/* ปุ่มโทรออกด่วน */}
                <a
                    href="tel:021234567"
                    title="โทรหาเราด่วน"
                    className="w-14 h-14 bg-gradient-to-r from-orange-500 to-rose-500 text-white rounded-full flex items-center justify-center shadow-[0_8px_20px_rgba(249,115,22,0.4)] hover:shadow-[0_12px_25px_rgba(249,115,22,0.5)] hover:-translate-y-1 transition-all group"
                >
                    <PhoneCall size={24} className="group-hover:animate-bounce" />
                </a>
            </div>

        </div>
    );
}