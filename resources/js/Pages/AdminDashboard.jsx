import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import AdminLayout from '../Layouts/AdminLayout';
import html2pdf from 'html2pdf.js';
import {
    CircleDollarSign, CheckCircle2, Zap, Users, ClipboardList,
    CreditCard, UserCog, Banknote, Clock, UserCheck,
    Phone, CheckCircle, X, MapPin, DownloadCloud, Loader2, Wind
} from 'lucide-react';

/* ─────────────────────────────────────────────
    Shared style tokens (ปรับ Font ให้ใหญ่และชัด)
───────────────────────────────────────────── */
const T = {
    card: { background: '#fff', border: '1px solid #dce8f7', borderRadius: '15px', boxShadow: 'inset 0 1px 0 rgba(255,255,255,.9)' },
    mono: { fontFamily: "'DM Mono', monospace" },
};

/* ─────────────────────────────────────────────
    StatCard
───────────────────────────────────────────── */
function StatCard({ label, value, unit, trend, trendColor = '#2563eb', accent, icon, onClick }) {
    return (
        <div
            onClick={onClick}
            className="sm-sc transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer"
            style={{ ...T.card, padding: '25px 22px', position: 'relative', overflow: 'hidden' }}
        >
            <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: '4px', background: accent, borderRadius: '15px 15px 0 0' }} />

            <div style={{ fontSize: '12px', fontWeight: 800, letterSpacing: '1px', textTransform: 'uppercase', color: '#8099b8', marginBottom: '15px' }}>
                {label}
            </div>
            <div style={{ fontSize: '36px', fontWeight: 900, color: '#08172e', letterSpacing: '-2px', lineHeight: 1 }}>
                {value}
                {unit && <span style={{ fontSize: '16px', fontWeight: 600, color: '#8099b8', letterSpacing: 0, marginLeft: '5px' }}>{unit}</span>}
            </div>
            {trend && (
                <div style={{ fontSize: '12px', fontWeight: 700, color: trendColor, marginTop: '12px', display: 'flex', alignItems: 'center', gap: '4px' }}>
                    {trend}
                </div>
            )}
            <div style={{ position: 'absolute', right: '-10px', bottom: '-15px', color: '#08172e', opacity: .06, pointerEvents: 'none' }}>
                {icon}
            </div>
        </div>
    );
}

/* ─────────────────────────────────────────────
    ColHeader & Pill
───────────────────────────────────────────── */
function ColHeader({ label, dotColor, count }) {
    return (
        <div style={{ ...T.card, padding: '15px 18px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderTop: `4px solid ${dotColor}` }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <div style={{ width: '10px', height: '10px', borderRadius: '50%', background: dotColor, flexShrink: 0 }} />
                <span style={{ fontSize: '15px', fontWeight: 800, color: '#08172e' }}>{label}</span>
            </div>
            <span style={{ ...T.mono, fontSize: '12px', fontWeight: 800, padding: '4px 12px', borderRadius: '20px', background: '#f0f4fb', border: '1px solid #dce8f7', color: '#1a6ff5' }}>
                {count}
            </span>
        </div>
    );
}

const PILL = {
    new: { background: 'rgba(245,158,11,.1)', color: '#92400e', border: '1px solid rgba(245,158,11,.3)' },
    wait: { background: 'rgba(244,63,94,.1)', color: '#9f1239', border: '1px solid rgba(244,63,94,.28)' },
    paid: { background: 'rgba(16,185,129,.1)', color: '#065f46', border: '1px solid rgba(16,185,129,.28)' },
};
function Pill({ type, children }) {
    return <span style={{ fontSize: '11px', fontWeight: 800, padding: '4px 12px', borderRadius: '20px', letterSpacing: '.3px', ...PILL[type] }}>{children}</span>;
}

/* ─────────────────────────────────────────────
    JobCard 
───────────────────────────────────────────── */
function JobCard({ job, pill, children }) {
    return (
        <div className="sm-jc" style={{ ...T.card, padding: '22px', borderLeft: '5px solid #1a6ff5' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '14px' }}>
                <span style={{ ...T.mono, fontSize: '11px', color: '#1a6ff5', fontWeight: 700, background: '#f0f7ff', padding: '4px 10px', borderRadius: '8px', border: '1px solid #bedbff' }}>
                    #{job.id}
                </span>
                {pill}
            </div>

            <div style={{ marginBottom: '15px' }}>
                <div style={{ fontSize: '18px', fontWeight: 800, color: '#08172e', marginBottom: '6px', letterSpacing: '-.4px' }}>
                    {job.customer?.name}
                </div>

                <div style={{ fontSize: '14px', color: '#1a6ff5', fontWeight: 700, display: 'flex', alignItems: 'center', gap: '6px', marginBottom: '10px' }}>
                    <Phone className="w-4 h-4" /> {job.customer?.phone}
                </div>

                <div style={{ fontSize: '13px', color: '#4a607e', background: '#f9fbff', padding: '10px', borderRadius: '10px', border: '1px solid #edf4fc', display: 'flex', gap: '8px', lineHeight: 1.5 }}>
                    <MapPin className="w-4 h-4 text-red-400 shrink-0 mt-0.5" />
                    <span className="font-medium">{job.customer?.address_detail || 'ไม่ได้ระบุที่อยู่'}</span>
                </div>
            </div>

            <div style={{ fontSize: '13px', color: '#08172e', padding: '10px 14px', background: 'linear-gradient(135deg, #f0f7ff, #fff)', border: '1px solid #dce8f7', borderRadius: '12px', marginBottom: '18px', fontWeight: 700, display: 'flex', alignItems: 'center', gap: '8px' }}>
                <Wind className="w-4 h-4 text-blue-500" /> {job.service?.service_name}
            </div>

            {children}
        </div>
    );
}

function EmptyCol({ icon, text }) {
    return (
        <div style={{ border: '2px dashed #c0d8f0', borderRadius: '14px', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '50px 20px', color: '#8099b8', fontSize: '14px', gap: '15px', textAlign: 'center', background: 'rgba(240,247,255,.5)' }}>
            <div style={{ opacity: .5 }}>{icon}</div>
            <div className="font-bold">{text}</div>
        </div>
    );
}

/* ─────────────────────────────────────────────
    AdminDashboard Main
───────────────────────────────────────────── */
export default function AdminDashboard({
    totalRevenue, completedCount, activeCount, techCount,
    pendingJobs, waitingPaymentJobs, paidJobs, techs,
    completedJobsList = [], activeJobsList = []
}) {
    const [activeModal, setActiveModal] = useState(null);
    const [isExporting, setIsExporting] = useState(false);

    const handleRequestPayment = (jobId) => router.post(`/admin/requests/${jobId}/request-payment`);
    const handleAssignTech = (e, jobId) => {
        e.preventDefault();
        router.post(`/admin/requests/${jobId}/assign-tech`, { tech_id: e.target.tech_id.value });
    };

    const now = new Date();
    const thaiTime = now.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
    const thaiDate = now.toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
    const col = { display: 'flex', flexDirection: 'column', gap: '15px' };
    const sora = { fontFamily: "'Sora', sans-serif" };

    // 📄 ระบบ Export PDF
    const handleExportPDF = () => {
        setIsExporting(true);
        const element = document.getElementById('pdf-report-content');
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: `SM_Cooling_Report_${activeModal}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save().then(() => setIsExporting(false));
    };

    // ✨ ฟังก์ชันดึงชื่อรายงาน
    const getReportTitle = () => {
        switch (activeModal) {
            case 'revenue': return 'รายงานสรุปรายได้บัญชี (REVENUE REPORT)';
            case 'completed': return 'รายงานประวัติงานเสร็จสิ้น (COMPLETED JOBS REPORT)';
            case 'active': return 'รายงานสถานะงานรอดำเนินการ (ACTIVE JOBS REPORT)';
            case 'techs': return 'ทำเนียบข้อมูลทีมช่าง (TECHNICIANS DIRECTORY)';
            default: return 'OFFICIAL OPERATION REPORT';
        }
    };

    const renderModalContent = () => {
        // ✨ สไตล์ตารางสำหรับปริ้นต์ (เน้นขาว-ดำ พิมพ์ชัด)
        const thStyle = "py-3 px-4 bg-slate-800 text-white font-bold text-xs uppercase tracking-wider border border-slate-800";
        const tdStyle = "py-3 px-4 text-sm text-slate-800 border-b border-slate-300";

        if (activeModal === 'revenue' || activeModal === 'completed') {
            const isRevenue = activeModal === 'revenue';
            return (
                <table className="w-full text-left border-collapse mb-6">
                    <thead>
                        <tr>
                            <th className={thStyle} style={{ width: '15%' }}>รหัสงาน</th>
                            <th className={thStyle} style={{ width: '30%' }}>ชื่อลูกค้า</th>
                            <th className={thStyle} style={{ width: '25%' }}>รายการบริการ</th>
                            <th className={thStyle} style={{ width: '15%' }}>ช่างผู้ดูแล</th>
                            {isRevenue && <th className={`${thStyle} text-right`} style={{ width: '15%' }}>ยอดเงิน (THB)</th>}
                        </tr>
                    </thead>
                    <tbody>
                        {completedJobsList.length > 0 ? completedJobsList.map((job, idx) => (
                            <tr key={job.id} className="even:bg-slate-50">
                                <td className={`${tdStyle} font-mono text-slate-600`}>#{job.id}</td>
                                <td className={`${tdStyle} font-bold`}>{job.customer?.name}</td>
                                <td className={tdStyle}>{job.service?.service_name}</td>
                                <td className={tdStyle}>{job.tech?.name || '-'}</td>
                                {isRevenue && <td className={`${tdStyle} text-right font-black`}>
                                    {parseFloat(job.total_price || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
                                </td>}
                            </tr>
                        )) : <tr><td colSpan={isRevenue ? 5 : 4} className="text-center py-10 font-bold text-slate-400">ไม่มีข้อมูล</td></tr>}
                    </tbody>
                </table>
            );
        }

        if (activeModal === 'active') {
            return (
                <table className="w-full text-left border-collapse mb-6">
                    <thead>
                        <tr>
                            <th className={thStyle} style={{ width: '15%' }}>รหัสงาน</th>
                            <th className={thStyle} style={{ width: '25%' }}>ชื่อลูกค้า</th>
                            <th className={thStyle} style={{ width: '40%' }}>สถานที่ปฏิบัติงาน</th>
                            <th className={thStyle} style={{ width: '20%' }}>สถานะปัจจุบัน</th>
                        </tr>
                    </thead>
                    <tbody>
                        {activeJobsList.length > 0 ? activeJobsList.map((job, idx) => (
                            <tr key={job.id} className="even:bg-slate-50">
                                <td className={`${tdStyle} font-mono text-slate-600`}>#{job.id}</td>
                                <td className={`${tdStyle} font-bold`}>{job.customer?.name}</td>
                                <td className={`${tdStyle} text-xs`}>{job.customer?.address_detail}</td>
                                <td className={`${tdStyle} font-bold`}>{job.tech?.name || 'รอจัดสรรช่าง'}</td>
                            </tr>
                        )) : <tr><td colSpan="4" className="text-center py-10 font-bold text-slate-400">ไม่มีข้อมูลงาน</td></tr>}
                    </tbody>
                </table>
            );
        }

        if (activeModal === 'techs') {
            return (
                <table className="w-full text-left border-collapse mb-6">
                    <thead>
                        <tr>
                            <th className={thStyle} style={{ width: '20%' }}>รหัสพนักงาน</th>
                            <th className={thStyle} style={{ width: '40%' }}>ชื่อ-นามสกุล</th>
                            <th className={thStyle} style={{ width: '40%' }}>ข้อมูลติดต่อ (เบอร์โทร/อีเมล)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {techs.length > 0 ? techs.map((tech, idx) => (
                            <tr key={tech.id} className="even:bg-slate-50">
                                <td className={`${tdStyle} font-mono text-slate-600`}>EMP-{tech.id.toString().padStart(4, '0')}</td>
                                <td className={`${tdStyle} font-bold`}>{tech.name}</td>
                                <td className={tdStyle}>{tech.phone || tech.email || '-'}</td>
                            </tr>
                        )) : <tr><td colSpan="3" className="text-center py-10 font-bold text-slate-400">ไม่มีข้อมูลช่าง</td></tr>}
                    </tbody>
                </table>
            );
        }
    };

    return (
        <AdminLayout>
            {/* Header */}
            <div className="flex items-end justify-between mb-10">
                <div>
                    <h1 style={{ fontSize: '32px', fontWeight: 900, letterSpacing: '-1px', color: '#08172e', margin: 0 }}>ภาพรวมการดำเนินงาน</h1>
                    <p style={{ fontSize: '15px', color: '#4a607e', marginTop: '6px', fontWeight: 500 }}>SM Cooling Center — จัดการงานบริการและคิวช่าง</p>
                </div>
                <div style={{ display: 'flex', gap: '10px', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px', fontFamily: "'DM Mono', monospace", fontSize: '12px', fontWeight: 700, background: 'rgba(16,185,129,.1)', border: '1px solid #10b981', color: '#059669', padding: '8px 16px', borderRadius: '10px' }}>
                        <span style={{ width: '8px', height: '8px', borderRadius: '50%', background: '#10b981' }} className="animate-pulse" /> Live
                    </div>
                    <div style={{ fontFamily: "'DM Mono', monospace", fontSize: '12px', fontWeight: 700, background: '#fff', border: '1px solid #c8daef', color: '#8099b8', padding: '8px 16px', borderRadius: '10px' }}>
                        อัปเดต {thaiTime} น. · {thaiDate}
                    </div>
                </div>
            </div>

            {/* Stat Cards */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: '18px', marginBottom: '40px' }}>
                <StatCard label="รายได้รวม" value={`฿${(totalRevenue || 0).toLocaleString('th-TH', { minimumFractionDigits: 2 })}`} trend="รายละเอียดบัญชีคลิก ➜" trendColor="#2563eb" accent="#1a6ff5" icon={<CircleDollarSign size={80} strokeWidth={1} />} onClick={() => setActiveModal('revenue')} />
                <StatCard label="ปิดจ๊อบแล้ว" value={completedCount || 0} unit=" งาน" trend="ดูประวัติงาน ➜" trendColor="#059669" accent="#10b981" icon={<CheckCircle size={80} strokeWidth={1} />} onClick={() => setActiveModal('completed')} />
                <StatCard label="รอดำเนินการ" value={activeCount || 0} unit=" งาน" trend="ดูสถานะช่าง ➜" trendColor="#d97706" accent="#f59e0b" icon={<Zap size={80} strokeWidth={1} />} onClick={() => setActiveModal('active')} />
                <StatCard label="ช่างในระบบ" value={techCount || 0} unit=" คน" trend="จัดการทีมช่าง ➜" trendColor="#7c3aed" accent="#8b5cf6" icon={<Users size={80} strokeWidth={1} />} onClick={() => setActiveModal('techs')} />
            </div>

            {/* Kanban Section */}
            <div style={{ fontSize: '12px', fontWeight: 800, letterSpacing: '1.2px', textTransform: 'uppercase', color: '#8099b8', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '12px' }}>
                ติดตามสถานะงาน <span style={{ flex: 1, height: '2px', background: '#e9f2ff' }} />
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: '22px' }}>
                {/* Column 1: งานเข้าใหม่ */}
                <div style={col}>
                    <ColHeader label="งานเข้าใหม่" dotColor="#f59e0b" count={pendingJobs?.length ?? 0} />
                    {pendingJobs?.map((job) => (
                        <JobCard key={job.id} job={job} pill={<Pill type="new">ใหม่</Pill>}>
                            <button onClick={() => handleRequestPayment(job.id)} style={{
                                ...sora, width: '100%', padding: '14px', borderRadius: '12px', fontSize: '14px', fontWeight: 800, border: 'none', cursor: 'pointer',
                                background: 'linear-gradient(135deg,#2b82ff,#1a6ff5)', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '8px',
                                boxShadow: '0 8px 20px rgba(26,111,245,0.25)'
                            }}>
                                <CreditCard size={18} /> แจ้งจ่ายเงิน
                            </button>
                        </JobCard>
                    ))}
                </div>

                {/* Column 2: รอโอน */}
                <div style={col}>
                    <ColHeader label="รอโอน / ตรวจสลิป" dotColor="#f43f5e" count={waitingPaymentJobs?.length ?? 0} />
                    {waitingPaymentJobs?.map((job) => (
                        <JobCard key={job.id} job={job} pill={job.status === 'paid' ? <Pill type="paid">แจ้งโอนแล้ว</Pill> : <Pill type="wait">รอโอน</Pill>}>
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                                {job.status === 'paid' ? (
                                    <>
                                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                            <span style={{ fontSize: '12px', color: '#059669', fontWeight: 800, display: 'flex', alignItems: 'center', gap: '4px' }}><Banknote size={15} /> แจ้งโอนแล้ว</span>
                                            <a href={`/admin/requests/${job.id}`} target="_blank" className="text-blue-600 font-bold text-xs underline">ดูรูปสลิป</a>
                                        </div>
                                        <button onClick={() => confirm('ยืนยันยอดเงิน?') && router.post(`/admin/confirm-payment/${job.id}`)} style={{
                                            ...sora, width: '100%', padding: '12px', borderRadius: '12px', fontSize: '14px', fontWeight: 800, border: 'none', cursor: 'pointer',
                                            background: '#10b981', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '8px'
                                        }}>
                                            <CheckCircle2 size={18} /> ยืนยันยอดเงินเข้า
                                        </button>
                                    </>
                                ) : (
                                    <div style={{ textAlign: 'center', padding: '10px', borderRadius: '10px', background: '#fff1f2', border: '1px solid #fecdd3', color: '#e11d48', fontWeight: 800, fontSize: '12px' }}>
                                        <Clock className="inline w-3 h-3 mr-1" /> กำลังรอชำระเงิน...
                                    </div>
                                )}
                            </div>
                        </JobCard>
                    ))}
                </div>

                {/* Column 3: ส่งช่าง */}
                <div style={col}>
                    <ColHeader label="พร้อมส่งช่าง" dotColor="#10b981" count={paidJobs?.length ?? 0} />
                    {paidJobs?.map((job) => (
                        <JobCard key={job.id} job={job} pill={<Pill type="paid">จ่ายแล้ว</Pill>}>
                            <form onSubmit={(e) => { e.preventDefault(); handleAssignTech(e, job.id); }} style={{ background: '#f0f7ff', border: '1px solid #c8daef', borderRadius: '12px', padding: '15px' }}>
                                <select name="tech_id" required style={{ ...sora, width: '100%', padding: '12px', borderRadius: '10px', fontSize: '14px', marginBottom: '10px', fontWeight: 600 }}>
                                    <option value="">-- เลือกช่างเข้างาน --</option>
                                    {techs?.map((tech) => <option key={tech.id} value={tech.id}>{tech.name}</option>)}
                                </select>
                                <button type="submit" style={{ ...sora, width: '100%', padding: '12px', borderRadius: '10px', fontSize: '14px', fontWeight: 800, background: '#0c1f38', color: '#fff', border: 'none', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '8px' }}>
                                    <UserCheck size={18} /> มอบหมายช่าง
                                </button>
                            </form>
                        </JobCard>
                    ))}
                </div>
            </div>

            {/* ✨ Modal & PDF Report (Corporate Formal Version) */}
            {activeModal && (
                <div className="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[2000] flex items-center justify-center p-6">
                    <div className="bg-slate-100 rounded-2xl w-full max-w-5xl max-h-[95vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 border border-slate-300">

                        {/* 🔘 โซนปุ่มควบคุมด้านบน (แอดมินเห็นตอนใช้งานเว็บ) */}
                        <div className="px-6 py-4 border-b border-slate-300 flex justify-between items-center bg-white">
                            <div className="flex items-center gap-3">
                                <div className="w-3 h-3 rounded-full bg-red-500"></div>
                                <div className="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div className="w-3 h-3 rounded-full bg-green-500"></div>
                                <span className="ml-2 font-mono text-xs font-bold text-slate-400">Document Preview</span>
                            </div>
                            <div className="flex gap-3">
                                <button onClick={handleExportPDF} disabled={isExporting} className="flex items-center gap-2 bg-slate-800 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-black transition-all disabled:opacity-50 text-sm">
                                    {isExporting ? <Loader2 className="animate-spin w-4 h-4" /> : <DownloadCloud className="w-4 h-4" />}
                                    {isExporting ? 'กำลังประมวลผล PDF...' : 'พิมพ์ / ดาวน์โหลด PDF'}
                                </button>
                                <button onClick={() => setActiveModal(null)} disabled={isExporting} className="p-2 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-all text-slate-600">
                                    <X size={20} />
                                </button>
                            </div>
                        </div>

                        {/* 📄 PDF Container (กระดาษ A4) */}
                        <div className="p-6 overflow-y-auto bg-slate-200 flex justify-center">

                            {/* กรอบกระดาษขาว */}
                            <div id="pdf-report-content" className="bg-white shadow-xl mx-auto" style={{ width: '210mm', minHeight: '297mm', padding: '20mm 15mm' }}>

                                {/* 📝 หัวกระดาษ (Letterhead) */}
                                <div className="border-b-2 border-slate-800 pb-6 mb-8 flex justify-between items-start">
                                    <div>
                                        <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">SM Cooling Center</h1>
                                        <p className="text-xs text-slate-600 mt-2 font-bold">บริษัท เอสเอ็ม คูลลิ่ง เซ็นเตอร์ จำกัด</p>
                                        <p className="text-xs text-slate-500 mt-1">123/45 ถ.ลำลูกกา ต.บึงคำพร้อย จ.ปทุมธานี 12150</p>
                                        <p className="text-xs text-slate-500">โทร: 02-123-4567 | อีเมล: contact@smcooling.com</p>
                                    </div>
                                    <div className="text-right">
                                        <h2 className="text-lg font-black text-slate-800 uppercase bg-slate-100 px-3 py-1 rounded border border-slate-300 inline-block mb-2">
                                            {getReportTitle()}
                                        </h2>
                                        <p className="text-xs text-slate-600 font-bold">วันที่ออกเอกสาร: <span className="font-normal">{thaiDate} {thaiTime} น.</span></p>
                                        <p className="text-xs text-slate-600 font-bold mt-0.5">เลขที่อ้างอิง: <span className="font-mono font-normal">REP-{new Date().getTime().toString().slice(-6)}</span></p>
                                    </div>
                                </div>

                                {/* 📄 ตารางข้อมูล (แทรกจากฟังก์ชัน) */}
                                {renderModalContent()}

                                {/* 💰 สรุปยอด (เฉพาะหน้ารายได้) */}
                                {activeModal === 'revenue' && (
                                    <div className="flex justify-end mb-16">
                                        <div className="w-[300px] border-2 border-slate-800 p-4">
                                            <div className="flex justify-between items-center mb-2">
                                                <span className="text-xs font-bold text-slate-600 uppercase">รวมรายการที่สำเร็จ</span>
                                                <span className="text-sm font-bold text-slate-900">{completedJobsList.length} รายการ</span>
                                            </div>
                                            <div className="border-t border-slate-300 my-2"></div>
                                            <div className="flex justify-between items-center">
                                                <span className="text-sm font-black text-slate-900 uppercase">ยอดรายได้สุทธิ</span>
                                                <span className="text-xl font-black text-slate-900">
                                                    ฿{(totalRevenue || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* ✍️ กรอบเซ็นชื่อ (Signatures) */}
                                <div className="mt-auto pt-10">
                                    <div className="grid grid-cols-2 gap-24 px-10">
                                        <div className="text-center">
                                            <div className="border-b border-slate-800 h-8 mb-2 w-full border-dashed"></div>
                                            <p className="text-xs font-bold text-slate-800">ผู้จัดทำรายงาน / พนักงานบัญชี</p>
                                            <p className="text-[10px] text-slate-500 mt-1">วันที่ _____/_____/_____</p>
                                        </div>
                                        <div className="text-center">
                                            <div className="border-b border-slate-800 h-8 mb-2 w-full border-dashed"></div>
                                            <p className="text-xs font-bold text-slate-800">ผู้มีอำนาจลงนาม / ผู้บริหาร</p>
                                            <p className="text-[10px] text-slate-500 mt-1">วันที่ _____/_____/_____</p>
                                        </div>
                                    </div>
                                </div>

                                {/* Watermark ท้ายกระดาษ */}
                                <div className="mt-16 text-center">
                                    <p className="text-[9px] text-slate-400 uppercase tracking-widest">
                                        เอกสารฉบับนี้ถูกสร้างขึ้นโดยระบบอัตโนมัติของ SM Cooling Center • Internal Use Only
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            )}

        </AdminLayout>
    );
}