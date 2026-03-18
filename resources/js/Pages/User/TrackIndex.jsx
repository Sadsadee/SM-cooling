import React from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { Phone, Search, ChevronLeft } from 'lucide-react';

export default function TrackIndex() {
    const { data, setData, get, processing, errors } = useForm({
        phone: '',
    });

    const handleSearch = (e) => {
        e.preventDefault();
        get('/track-status/search');
    };

    return (
        <div className="min-h-screen bg-slate-50 font-['Sora'] flex items-center justify-center px-6">
            <Head title="ติดตามสถานะงาน - SM Cooling" />

            <div className="max-w-md w-full space-y-8">
                <div className="text-center">
                    <Link
                        href="/"
                        className="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 mb-6 font-bold text-sm transition-colors"
                    >
                        <ChevronLeft size={18} /> กลับหน้าหลัก
                    </Link>
                    <h1 className="text-3xl font-black text-slate-900 mb-2">ติดตามสถานะงาน</h1>
                    <p className="text-slate-500 font-medium">กรอกเบอร์โทรศัพท์ของคุณเพื่อดูประวัติการซ่อม</p>
                </div>

                <form onSubmit={handleSearch} className="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-slate-100 space-y-6">
                    <div>
                        <label className="block text-xs font-black text-slate-400 uppercase mb-3 tracking-widest">เบอร์โทรศัพท์</label>
                        <div className="relative">
                            <Phone className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                            <input
                                type="tel"
                                value={data.phone}
                                onChange={e => setData('phone', e.target.value)}
                                placeholder="08x-xxx-xxxx"
                                className="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-blue-500 font-bold text-lg"
                                required
                            />
                        </div>
                        {errors.phone && <p className="text-red-500 text-xs mt-2">{errors.phone}</p>}
                    </div>

                    <button
                        disabled={processing}
                        className="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2"
                    >
                        <Search size={20} /> {processing ? 'กำลังค้นหา...' : 'ค้นหาประวัติงาน'}
                    </button>
                </form>
            </div>
        </div>
    );
}