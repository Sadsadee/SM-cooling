import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Clock, ChevronRight, Wind, Wrench } from 'lucide-react';

export default function TrackResults({ customer, jobs }) {
    return (
        <div className="min-h-screen bg-slate-50 font-['Sora'] py-12 px-6">
            <Head title="ประวัติการแจ้งซ่อม" />
            <div className="max-w-2xl mx-auto space-y-8">
                
                <div className="flex items-center justify-between">
                    <Link href="/track-status" className="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 text-slate-400 hover:text-blue-600">
                        <ArrowLeft size={20} />
                    </Link>
                    <div className="text-right">
                        <p className="text-xs font-black text-slate-400 uppercase">คุณลูกค้า</p>
                        <p className="text-lg font-black text-slate-900">{customer.name}</p>
                    </div>
                </div>

                <div className="space-y-4">
                    <h2 className="text-xl font-black text-slate-800 mb-6">ประวัติการแจ้งซ่อม ({jobs.length})</h2>
                    
                    {jobs.map(job => (
                        <Link 
                            key={job.id} 
                            href={`/customer/requests/${job.id}`}
                            className="block bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group"
                        >
                            <div className="flex justify-between items-start">
                                <div className="flex gap-4">
                                    <div className="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                        {job.service.service_name.includes('ล้าง') ? <Wind size={24} /> : <Wrench size={24} />}
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">งาน #{job.id}</p>
                                        <p className="font-black text-slate-800 text-lg">{job.service.service_name}</p>
                                        <div className="flex items-center gap-2 text-xs font-bold text-slate-400 mt-1">
                                            <Clock size={14} /> {new Date(job.created_at).toLocaleDateString('th-TH')}
                                        </div>
                                    </div>
                                </div>
                                <div className="flex flex-col items-end gap-2">
                                    <span className={`text-[10px] font-black px-3 py-1 rounded-full uppercase ${
                                        job.status === 'completed' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600'
                                    }`}>
                                        {job.status}
                                    </span>
                                    <ChevronRight size={18} className="text-slate-300 group-hover:text-blue-600 transition-colors" />
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </div>
    );
}