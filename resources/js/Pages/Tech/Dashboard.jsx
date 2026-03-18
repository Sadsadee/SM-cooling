import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';

const Dashboard = ({ auth, jobs }) => {
    const user = auth?.user;

    return (
        <>
            <Head title="Tech Dashboard" />

            <div className="py-6 pb-24">
                <div className="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

                    {/* 💳 สรุปงานด้านบน (Welcome Card) */}
                    <div className="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-6 text-white shadow-[0_10px_20px_rgba(37,99,235,0.2)] mx-4 sm:mx-0 flex justify-between items-center relative overflow-hidden">
                        {/* ของตกแต่งพื้นหลังให้ดูมีมิติ */}
                        <div className="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-5 rounded-full blur-xl"></div>
                        <div className="absolute right-10 -bottom-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>

                        <div className="relative z-10">
                            <p className="text-blue-200 font-bold text-sm mb-1 uppercase tracking-widest">ยินดีต้อนรับ,</p>
                            <h3 className="text-2xl font-black tracking-tight">{user?.name || 'ช่างเทคนิค'}</h3>
                        </div>
                        <div className="relative z-10 bg-white/20 backdrop-blur-md px-5 py-4 rounded-[1.5rem] text-center border border-white/20 shadow-inner">
                            <p className="text-4xl font-black leading-none drop-shadow-md">{jobs?.length || 0}</p>
                            <p className="text-[10px] font-bold mt-2 uppercase tracking-widest opacity-90">งานวันนี้</p>
                        </div>
                    </div>

                    {/* 📋 รายการงาน (Job List) */}
                    <div className="px-4 sm:px-0 space-y-4">
                        <div className="flex items-center justify-between ml-2 mb-2">
                            <h3 className="text-lg font-black text-gray-800">📋 คิวงานปัจจุบัน</h3>
                        </div>

                        {(!jobs || jobs.length === 0) ? (
                            <div className="bg-white rounded-[2rem] p-10 text-center shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                                <span className="text-6xl block mb-4 drop-shadow-sm">🎮</span>
                                <p className="text-gray-800 font-black text-xl">พักผ่อนให้เต็มที่!</p>
                                <p className="text-gray-400 font-bold text-sm mt-2">ยังไม่มีงานค้างในระบบครับ</p>
                            </div>
                        ) : (
                            jobs.map(job => (
                                <div key={job.id} className="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative active:scale-[0.98] transition-transform duration-200">
                                    {/* แถบสีสถานะด้านบนขวา (Pill) */}
                                    <div className="p-6">
                                        <div className="flex justify-between items-center mb-4">
                                            <span className="text-gray-400 font-black text-sm uppercase tracking-wider">
                                                งาน #{job.id}
                                            </span>
                                            <span className="bg-orange-100 text-orange-600 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest">
                                                {job.status === 'in_progress' ? 'กำลังดำเนินการ' : job.status}
                                            </span>
                                        </div>

                                        {/* ชื่องาน */}
                                        <h4 className="text-2xl font-black text-gray-800 mb-5 leading-tight">
                                            {job.service?.service_name || 'บริการทั่วไป'}
                                        </h4>

                                        {/* ข้อมูลลูกค้า (เอาพื้นหลังออก เน้นความคลีน) */}
                                        <div className="space-y-3 mb-6">
                                            <div className="flex items-center gap-3">
                                                <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-sm">👤</div>
                                                <div>
                                                    <p className="font-bold text-gray-700 text-sm">{job.customer?.name}</p>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-3">
                                                <div className="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-sm">📞</div>
                                                <div>
                                                    <a href={`tel:${job.customer?.phone}`} className="font-black text-blue-600 text-base hover:underline">
                                                        {job.customer?.phone || 'ไม่ระบุเบอร์'}
                                                    </a>
                                                </div>
                                            </div>

                                            <div className="flex items-start gap-3">
                                                <div className="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-sm mt-0.5">📍</div>
                                                <div className="flex-1">
                                                    <p className="text-sm font-bold text-gray-600 leading-relaxed">
                                                        {job.customer?.address_detail} <br /> จ.{job.customer?.province}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {/* ปุ่มกด */}
                                        <div className="mt-2">
                                            <Link href={`/tech/requests/${job.id}`}
                                                className="flex items-center justify-center gap-2 w-full bg-gray-900 text-white font-black py-4 rounded-[1.2rem] hover:bg-black transition-all shadow-md text-base">
                                                <span>🚀</span> ดูรายละเอียดงาน
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </>
    );
};

// ใช้ Persistent Layout ป้องกันหน้าจอซ้อน
Dashboard.layout = page => <AdminLayout children={page} />;

export default Dashboard;