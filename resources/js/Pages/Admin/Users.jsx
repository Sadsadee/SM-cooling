import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, router } from '@inertiajs/react';
import { Users as UsersIcon, UserCircle, Mail, Phone, MapPin, Trash2, Inbox } from 'lucide-react';

export default function Users({ users }) {
    const handleDelete = (id, name) => {
        if (!id) {
            alert("เกิดข้อผิดพลาด: ไม่พบ ID ของข้อมูลนี้");
            return;
        }
        if (confirm('⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลของ "' + name + '" ?')) {
            router.delete('/admin/users/' + id, {
                preserveScroll: true,
                onSuccess: () => alert('🗑️ ลบข้อมูลเรียบร้อยแล้ว')
            });
        }
    };

    return (
        <AdminLayout>
            <Head title="จัดการข้อมูลลูกค้า" />

            <div className="space-y-10 pb-20">
                <div className="flex items-center gap-4 mb-8">
                    <UsersIcon size={36} className="text-gray-800" />
                    <h1 className="text-3xl font-black text-gray-800">จัดการข้อมูลลูกค้า</h1>
                </div>

                <section className="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 lg:p-10">
                    <div className="flex justify-between items-center mb-8">
                        <div className="flex items-center gap-4">
                            <div className="bg-purple-100 text-purple-600 w-16 h-16 flex items-center justify-center rounded-3xl shadow-sm">
                                <UserCircle size={32} />
                            </div>
                            <div>
                                <h2 className="text-2xl font-black text-gray-800">รายชื่อลูกค้าในระบบ</h2>
                                <p className="text-sm font-bold text-gray-400 mt-1">ข้อมูลการติดต่อและที่อยู่สำหรับให้บริการ</p>
                            </div>
                        </div>
                        <span className="bg-purple-600 text-white px-5 py-2 rounded-full text-sm font-black shadow-md">
                            {users?.length || 0} คน
                        </span>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="text-sm font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">
                                    <th className="px-5 py-4 rounded-tl-2xl">ข้อมูลลูกค้า</th>
                                    <th className="px-5 py-4">เบอร์โทรศัพท์</th>
                                    <th className="px-5 py-4">ที่อยู่ปัจจุบัน</th>
                                    <th className="px-5 py-4 text-center rounded-tr-2xl">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                {users?.map(user => (
                                    <tr key={user.id} className="border-b border-gray-50 hover:bg-purple-50/30 transition">
                                        <td className="px-5 py-5">
                                            <div className="font-black text-gray-800 text-lg">{user.name}</div>
                                            <div className="text-gray-500 font-medium text-sm mt-1 flex items-center gap-1.5">
                                                <Mail size={14} /> {user.email}
                                            </div>
                                        </td>
                                        <td className="px-5 py-5 font-black text-gray-700 text-base">
                                            {user.phone ? (
                                                <span className="flex items-center gap-2"><Phone size={16} className="text-pink-600" /> {user.phone}</span>
                                            ) : (
                                                <span className="text-gray-300 font-normal">- ไม่มีข้อมูล -</span>
                                            )}
                                        </td>
                                        <td className="px-5 py-5 text-gray-600 text-sm leading-relaxed max-w-xs">
                                            {user.address_detail ? (
                                                <div className="flex items-start gap-2">
                                                    <MapPin size={16} className="text-red-400 mt-0.5 shrink-0" />
                                                    <span>
                                                        {user.address_detail} {user.subdistrict} {user.district} <br />
                                                        จ.{user.province} {user.zipcode}
                                                    </span>
                                                </div>
                                            ) : (
                                                <span className="text-gray-300">- ยังไม่ระบุที่อยู่ -</span>
                                            )}
                                        </td>
                                        <td className="px-5 py-5 text-center">
                                            <button
                                                onClick={() => handleDelete(user.id, user.name)}
                                                className="text-red-400 hover:text-white hover:bg-red-500 px-4 py-2 rounded-xl transition flex justify-center items-center mx-auto"
                                            >
                                                <Trash2 size={20} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {(!users || users.length === 0) && (
                                    <tr>
                                        <td colSpan="4" className="text-center py-16 text-gray-400 font-bold text-lg bg-gray-50/30 flex flex-col items-center justify-center gap-3">
                                            <Inbox size={40} className="opacity-50" />
                                            ยังไม่มีลูกค้าลงทะเบียนในระบบ
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