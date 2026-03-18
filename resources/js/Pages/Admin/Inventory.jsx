import React, { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm, router } from '@inertiajs/react';
import { PackageOpen, Settings, Droplet, Sparkles, Plus, Box } from 'lucide-react';

// 🛠️ 1. Component: แถวตารางอะไหล่
const PartRow = ({ part }) => {
    const [data, setData] = useState({
        part_name: part.part_name,
        price: part.price,
        stock: part.stock
    });

    const handleUpdate = () => {
        router.put(`/admin/inventory/part/${part.id}`, data, {
            preserveScroll: true,
            onSuccess: () => alert(`✨ อัปเดต ${data.part_name} เรียบร้อย!`)
        });
    };

    return (
        <tr className="hover:bg-orange-50/50 transition border-b border-gray-100">
            <td className="px-5 py-4">
                <input type="text" value={data.part_name} onChange={e => setData({ ...data, part_name: e.target.value })}
                    className="border-transparent hover:border-orange-200 focus:border-orange-500 rounded-xl text-base font-bold text-gray-700 w-full bg-transparent focus:bg-white transition outline-none px-4 py-3 shadow-sm focus:shadow-md" />
            </td>
            <td className="px-5 py-4">
                <div className="flex items-center gap-2">
                    <span className="text-gray-400 font-bold text-sm">฿</span>
                    <input type="number" value={data.price} onChange={e => setData({ ...data, price: e.target.value })}
                        className="border-transparent hover:border-orange-200 focus:border-orange-500 rounded-xl text-base font-bold text-gray-700 w-full bg-transparent focus:bg-white transition outline-none px-4 py-3 shadow-sm focus:shadow-md" />
                </div>
            </td>
            <td className="px-5 py-4">
                <div className="flex items-center gap-2">
                    <input type="number" value={data.stock} onChange={e => setData({ ...data, stock: e.target.value })}
                        className="border-transparent hover:border-orange-200 focus:border-orange-500 rounded-xl text-base font-bold text-gray-700 w-24 bg-transparent focus:bg-white transition outline-none px-4 py-3 shadow-sm focus:shadow-md" />
                    <span className="text-gray-400 font-bold text-sm">ชิ้น</span>
                </div>
            </td>
            <td className="px-5 py-4 text-center">
                <button onClick={handleUpdate} className="text-orange-600 font-black text-sm hover:text-white bg-orange-100 px-5 py-2.5 rounded-xl hover:bg-orange-500 transition shadow-sm hover:shadow-lg">
                    อัปเดต
                </button>
            </td>
        </tr>
    );
};

// 🛠️ 2. Component: แถวตารางบริการ
const ServiceRow = ({ service }) => {
    const [data, setData] = useState({
        service_name: service.service_name,
        base_price: service.base_price
    });

    const handleUpdate = () => {
        router.put(`/admin/inventory/service/${service.id}`, data, {
            preserveScroll: true,
            onSuccess: () => alert(`✨ อัปเดต ${data.service_name} เรียบร้อย!`)
        });
    };

    return (
        <tr className="hover:bg-blue-50/50 transition border-b border-gray-100">
            <td className="px-5 py-4">
                <input type="text" value={data.service_name} onChange={e => setData({ ...data, service_name: e.target.value })}
                    className="border-transparent hover:border-blue-200 focus:border-blue-500 rounded-xl text-base font-bold text-gray-700 w-full bg-transparent focus:bg-white transition outline-none px-4 py-3 shadow-sm focus:shadow-md" />
            </td>
            <td className="px-5 py-4">
                <div className="flex items-center gap-2">
                    <span className="text-gray-400 font-bold text-sm">฿</span>
                    <input type="number" value={data.base_price} onChange={e => setData({ ...data, base_price: e.target.value })}
                        className="border-transparent hover:border-blue-200 focus:border-blue-500 rounded-xl text-base font-bold text-gray-700 w-full bg-transparent focus:bg-white transition outline-none px-4 py-3 shadow-sm focus:shadow-md" />
                </div>
            </td>
            <td className="px-5 py-4 text-center">
                <button onClick={handleUpdate} className="text-blue-600 font-black text-sm hover:text-white bg-blue-100 px-5 py-2.5 rounded-xl hover:bg-blue-600 transition shadow-sm hover:shadow-lg">
                    อัปเดตราคา
                </button>
            </td>
        </tr>
    );
};

// 🌟 3. หน้าจอหลัก (Main Component)
export default function Inventory({ parts, services }) {
    const partForm = useForm({ part_name: '', price: '', stock: '' });
    const serviceForm = useForm({ service_name: '', base_price: '' });

    const totalPartValue = parts.reduce((sum, p) => sum + (p.price * p.stock), 0);
    const totalStockCount = parts.reduce((sum, p) => sum + Number(p.stock), 0);

    const submitNewPart = (e) => {
        e.preventDefault();
        partForm.post('/admin/inventory/part', {
            preserveScroll: true,
            onSuccess: () => {
                alert('📦 เพิ่มอะไหล่ลงคลังเรียบร้อย!');
                partForm.reset();
            }
        });
    };

    const submitNewService = (e) => {
        e.preventDefault();
        serviceForm.post('/admin/inventory/service', {
            preserveScroll: true,
            onSuccess: () => {
                alert('💧 เพิ่มบริการใหม่เรียบร้อย!');
                serviceForm.reset();
            }
        });
    };

    return (
        <AdminLayout>
            <Head title="คลังอะไหล่และบริการ" />

            <div className="space-y-12 pb-24">
                {/* Header หน้าหลัก */}
                <div className="flex items-center gap-5 bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                    <div className="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 text-gray-700">
                        <PackageOpen size={40} strokeWidth={1.5} />
                    </div>
                    <div>
                        <h1 className="text-3xl font-black text-gray-800 tracking-tight">บริการและคลังอะไหล่</h1>
                        <p className="text-sm font-bold text-gray-500 mt-2">จัดการรายการอะไหล่ สต็อกสินค้า และกำหนดราคาบริการมาตรฐาน</p>
                    </div>
                </div>

                {/* ── ส่วนที่ 1: คลังอะไหล่ ── */}
                <section className="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden p-8 lg:p-10">
                    <div className="flex flex-col lg:flex-row justify-between lg:items-center gap-6 mb-10 border-b border-gray-100 pb-8">
                        <div className="flex items-center gap-4">
                            <div className="bg-orange-100 text-orange-600 w-16 h-16 flex items-center justify-center rounded-3xl shadow-sm">
                                <Settings size={32} />
                            </div>
                            <div>
                                <h2 className="text-2xl font-black text-gray-800">คลังอะไหล่</h2>
                                <p className="text-sm font-bold text-gray-400 mt-1">{parts.length} รายการในระบบ</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-4">
                            <div className="bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100">
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1"><Box size={12}/> ชิ้นส่วนทั้งหมด</p>
                                <p className="text-xl font-black text-gray-800 mt-1">{totalStockCount} <span className="text-sm font-bold text-gray-400">ชิ้น</span></p>
                            </div>
                            <div className="bg-green-50 px-5 py-3 rounded-2xl border border-green-100">
                                <p className="text-[10px] font-bold text-green-600/70 uppercase tracking-widest">มูลค่ารวมในคลัง</p>
                                <p className="text-xl font-black text-green-700 mt-1">฿ {totalPartValue.toLocaleString()}</p>
                            </div>
                        </div>
                    </div>

                    {/* กล่องเพิ่มอะไหล่ใหม่ */}
                    <div className="mb-10 p-8 border-2 border-dashed border-orange-200 rounded-[2rem] bg-orange-50/30">
                        <h3 className="text-lg font-black text-orange-700 mb-6 flex items-center gap-2">
                            <Sparkles size={20} /> เพิ่มอะไหล่ใหม่
                        </h3>
                        <form onSubmit={submitNewPart} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div className="col-span-1 md:col-span-1">
                                <label className="block text-xs font-bold text-orange-600/70 uppercase tracking-widest mb-2 ml-2">ชื่ออะไหล่</label>
                                <input type="text" placeholder="ระบุชื่ออะไหล่..." required
                                    value={partForm.data.part_name} onChange={e => partForm.setData('part_name', e.target.value)}
                                    className="border-orange-200 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white shadow-sm" />
                            </div>
                            <div className="col-span-1 md:col-span-1">
                                <label className="block text-xs font-bold text-orange-600/70 uppercase tracking-widest mb-2 ml-2">ราคา (บาท)</label>
                                <input type="number" placeholder="0.00" required
                                    value={partForm.data.price} onChange={e => partForm.setData('price', e.target.value)}
                                    className="border-orange-200 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white shadow-sm" />
                            </div>
                            <div className="col-span-1 md:col-span-1">
                                <label className="block text-xs font-bold text-orange-600/70 uppercase tracking-widest mb-2 ml-2">สต็อกเริ่มต้น</label>
                                <input type="number" placeholder="0" required
                                    value={partForm.data.stock} onChange={e => partForm.setData('stock', e.target.value)}
                                    className="border-orange-200 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white shadow-sm" />
                            </div>
                            <div className="col-span-1 md:col-span-1 pb-1">
                                <button type="submit" disabled={partForm.processing}
                                    className="bg-orange-600 text-white w-full py-4 rounded-2xl text-base font-black shadow-lg shadow-orange-200 hover:bg-orange-700 hover:-translate-y-1 transition active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2">
                                    {partForm.processing ? 'กำลังบันทึก...' : <><Plus size={18} /> เพิ่มลงคลัง</>}
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* ตารางแสดงข้อมูล */}
                    <div className="overflow-x-auto bg-white rounded-3xl border border-gray-100">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-200">
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">ชื่ออะไหล่ (คลิกเพื่อแก้ไข)</th>
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">ราคาจำหน่าย</th>
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">จำนวนคงเหลือ</th>
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                {parts.map(part => <PartRow key={part.id} part={part} />)}
                                {parts.length === 0 && (
                                    <tr><td colSpan="4" className="text-center py-10 text-gray-400 font-bold">ไม่มีรายการอะไหล่ในระบบ</td></tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </section>

                {/* ── ส่วนที่ 2: อัตราค่าบริการ ── */}
                <section className="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden p-8 lg:p-10">
                    <div className="flex flex-col lg:flex-row justify-between lg:items-center gap-6 mb-10 border-b border-gray-100 pb-8">
                        <div className="flex items-center gap-4">
                            <div className="bg-blue-100 text-blue-600 w-16 h-16 flex items-center justify-center rounded-3xl shadow-sm">
                                <Droplet size={32} />
                            </div>
                            <div>
                                <h2 className="text-2xl font-black text-gray-800">อัตราค่าบริการหลัก</h2>
                                <p className="text-sm font-bold text-gray-400 mt-1">{services.length} บริการที่เปิดใช้งาน</p>
                            </div>
                        </div>
                    </div>

                    {/* กล่องเพิ่มบริการใหม่ */}
                    <div className="mb-10 p-8 border-2 border-dashed border-blue-200 rounded-[2rem] bg-blue-50/30">
                        <h3 className="text-lg font-black text-blue-700 mb-6 flex items-center gap-2">
                            <Sparkles size={20} /> เพิ่มบริการใหม่
                        </h3>
                        <form onSubmit={submitNewService} className="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div className="col-span-1">
                                <label className="block text-xs font-bold text-blue-600/70 uppercase tracking-widest mb-2 ml-2">ชื่อบริการ / ประเภทงาน</label>
                                <input type="text" placeholder="ระบุชื่อบริการ..." required
                                    value={serviceForm.data.service_name} onChange={e => serviceForm.setData('service_name', e.target.value)}
                                    className="border-blue-200 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white shadow-sm" />
                            </div>
                            <div className="col-span-1">
                                <label className="block text-xs font-bold text-blue-600/70 uppercase tracking-widest mb-2 ml-2">ค่าบริการ (บาท)</label>
                                <input type="number" placeholder="0.00" required
                                    value={serviceForm.data.base_price} onChange={e => serviceForm.setData('base_price', e.target.value)}
                                    className="border-blue-200 rounded-2xl text-base w-full outline-none px-5 py-4 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white shadow-sm" />
                            </div>
                            <div className="col-span-1 pb-1">
                                <button type="submit" disabled={serviceForm.processing}
                                    className="bg-blue-600 text-white w-full py-4 rounded-2xl text-base font-black shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2">
                                    {serviceForm.processing ? 'กำลังบันทึก...' : <><Plus size={18} /> เปิดบริการใหม่</>}
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* ตารางแสดงข้อมูลบริการ */}
                    <div className="overflow-x-auto bg-white rounded-3xl border border-gray-100">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-200">
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">ชื่อบริการ (คลิกเพื่อแก้ไข)</th>
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">ค่าบริการมาตรฐาน</th>
                                    <th className="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                {services.map(service => <ServiceRow key={service.id} service={service} />)}
                                {services.length === 0 && (
                                    <tr><td colSpan="3" className="text-center py-10 text-gray-400 font-bold">ไม่มีรายการบริการในระบบ</td></tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </AdminLayout>
    );
}