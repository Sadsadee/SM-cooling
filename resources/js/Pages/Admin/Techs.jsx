import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm, router } from '@inertiajs/react';
import { UserCog, UserPlus, Sparkles, ShieldCheck, Trash2, Inbox } from 'lucide-react';

export default function Techs({ techs }) {
    const { data, setData, post, processing, reset, errors } = useForm({
        name: '', email: '', password: '', role: 'tech'
    });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/techs', {
            preserveScroll: true,
            onSuccess: () => {
                alert('✨ ลงทะเบียนช่างใหม่เรียบร้อยแล้ว!');
                reset('name', 'email', 'password');
            }
        });
    };

    const handleDelete = (id, name) => {
        if (!id) {
            alert("เกิดข้อผิดพลาด: ไม่พบ ID ของช่างคนนี้");
            return;
        }
        if (confirm('⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบช่าง "' + name + '" ? \n(ไม่สามารถกู้คืนได้)')) {
            router.delete('/admin/users/' + id, {
                preserveScroll: true,
                onSuccess: () => alert('🗑️ ลบข้อมูลช่างเรียบร้อยแล้ว')
            });
        }
    };

    return (
        <AdminLayout>
            <Head title="จัดการข้อมูลทีมช่าง" />

            <div className="space-y-10 pb-20">
                <div className="flex items-center gap-4 mb-8">
                    <UserCog size={36} className="text-gray-800" />
                    <h1 className="text-3xl font-black text-gray-800">จัดการข้อมูลทีมช่าง</h1>
                </div>

                {/* ── ส่วนที่ 1: ฟอร์มลงทะเบียนช่างใหม่ ── */}
                <section className="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 lg:p-10">
                    <div className="flex items-center gap-5 mb-8">
                        <div className="bg-blue-600 text-white w-16 h-16 flex items-center justify-center rounded-3xl shadow-lg shadow-blue-200">
                            <UserPlus size={32} />
                        </div>
                        <div>
                            <h2 className="text-2xl font-black text-gray-800">ลงทะเบียนช่างใหม่</h2>
                            <p className="text-sm font-bold text-gray-400 mt-1">สร้างบัญชีผู้ใช้งานให้ช่างเพื่อใช้รับงานผ่านมือถือ</p>
                        </div>
                    </div>

                    <form onSubmit={submit} className="grid grid-cols-1 md:grid-cols-4 gap-6 items-start bg-blue-50/40 p-8 rounded-[2rem] border border-blue-50">
                        <div className="col-span-1">
                            <label className="block text-sm font-bold text-gray-600 uppercase tracking-widest mb-3 ml-2">ชื่อ-นามสกุล</label>
                            <input type="text" placeholder="ระบุชื่อช่าง..." required
                                value={data.name} onChange={e => setData('name', e.target.value)}
                                className="border-blue-100 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white shadow-sm" />
                            {errors.name && <p className="text-red-500 text-sm mt-2 ml-2 font-bold">{errors.name}</p>}
                        </div>

                        <div className="col-span-1">
                            <label className="block text-sm font-bold text-gray-600 uppercase tracking-widest mb-3 ml-2">อีเมลผู้ใช้งาน</label>
                            <input type="email" placeholder="tech@smcooling.com" required
                                value={data.email} onChange={e => setData('email', e.target.value)}
                                className="border-blue-100 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white shadow-sm" />
                            {errors.email && <p className="text-red-500 text-sm mt-2 ml-2 font-bold">{errors.email}</p>}
                        </div>

                        <div className="col-span-1">
                            <label className="block text-sm font-bold text-gray-600 uppercase tracking-widest mb-3 ml-2">รหัสผ่านเริ่มต้น</label>
                            <input type="password" placeholder="••••••••" required
                                value={data.password} onChange={e => setData('password', e.target.value)}
                                className="border-blue-100 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white shadow-sm" />
                            {errors.password && <p className="text-red-500 text-sm mt-2 ml-2 font-bold">{errors.password}</p>}
                        </div>

                        <div className="col-span-1 pt-8">
                            <button type="submit" disabled={processing}
                                className="bg-blue-600 text-white w-full py-4 rounded-2xl text-lg font-black shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2 mt-1">
                                {processing ? 'กำลังบันทึก...' : <><Sparkles size={20} /> ลงทะเบียน</>}
                            </button>
                        </div>
                    </form>
                </section>

                {/* ── ส่วนที่ 2: รายชื่อช่างปัจจุบัน ── */}
                <section className="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 lg:p-10">
                    <div className="flex justify-between items-center mb-8">
                        <div className="flex items-center gap-4">
                            <div className="bg-gray-100 text-gray-600 w-16 h-16 flex items-center justify-center rounded-3xl">
                                <ShieldCheck size={32} />
                            </div>
                            <h2 className="text-2xl font-black text-gray-800">ช่างในระบบปัจจุบัน</h2>
                        </div>
                        <span className="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-black shadow-sm">
                            {techs?.length || 0} คน
                        </span>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="text-sm font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    <th className="px-5 py-4">สถานะ</th>
                                    <th className="px-5 py-4">ชื่อช่าง</th>
                                    <th className="px-5 py-4">อีเมล (เข้าสู่ระบบ)</th>
                                    <th className="px-5 py-4 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                {techs?.map(tech => (
                                    <tr key={tech.id} className="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                        <td className="px-5 py-5">
                                            <span className="bg-green-100 text-green-700 text-xs font-black px-4 py-2 rounded-full flex items-center w-max gap-2 shadow-sm">
                                                <span className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                พร้อมรับงาน
                                            </span>
                                        </td>
                                        <td className="px-5 py-5 font-black text-gray-800 text-lg">{tech.name}</td>
                                        <td className="px-5 py-5 text-gray-500 font-medium text-base">{tech.email}</td>
                                        <td className="px-5 py-5 text-center">
                                            <button
                                                onClick={() => handleDelete(tech.id, tech.name)}
                                                className="text-red-400 hover:text-white hover:bg-red-500 px-4 py-2 rounded-xl transition shadow-sm border border-red-100 hover:border-transparent flex justify-center items-center mx-auto"
                                                title="ลบข้อมูลช่าง"
                                            >
                                                <Trash2 size={20} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {(!techs || techs.length === 0) && (
                                    <tr>
                                        <td colSpan="4" className="text-center py-12 text-gray-400 font-bold text-lg flex flex-col items-center justify-center gap-3">
                                            <Inbox size={40} className="opacity-50" />
                                            ยังไม่มีช่างในระบบ
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </AdminLayout>
    );
}