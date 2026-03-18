import React from 'react';
import { useForm, Head } from '@inertiajs/react';

export default function Login() {
    // ใช้ useForm ของ Inertia เพื่อจัดการข้อมูลและ Error
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login'); // ส่งข้อมูลไปที่ฟังก์ชัน store ใน AuthenticatedSessionController
    };

    return (
        <div style={{ 
            minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center',
            background: '#f8fafc', fontFamily: "'Sora', sans-serif" 
        }}>
            <Head title="เข้าสู่ระบบ - SM Cooling" />
            
            <div style={{ 
                width: '100%', maxWidth: '400px', background: '#fff', padding: '40px',
                borderRadius: '30px', boxShadow: '0 20px 40px rgba(0,0,0,0.05)',
                border: '1px solid #eef2f7'
            }}>
                {/* Logo & Header */}
                <div style={{ textAlign: 'center', marginBottom: '35px' }}>
                    <div style={{ fontSize: '40px', marginBottom: '10px' }}>❄️</div>
                    <h2 style={{ fontWeight: 800, color: '#1e3a8a', fontSize: '22px', letterSpacing: '-0.5px' }}>
                        SM Cooling Center
                    </h2>
                    <p style={{ color: '#94a3b8', fontSize: '12px', fontWeight: 600, marginTop: '5px' }}>
                        ADMIN PORTAL SYSTEM
                    </p>
                </div>

                <form onSubmit={submit} style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
                    {/* Email Input */}
                    <div>
                        <label style={{ fontSize: '11px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '8px', display: 'block' }}>อีเมล</label>
                        <input 
                            type="email" 
                            value={data.email}
                            onChange={e => setData('email', e.target.value)}
                            style={{ 
                                width: '100%', padding: '12px 16px', borderRadius: '12px',
                                border: '1px solid #e2e8f0', background: '#fcfdfe', outline: 'none'
                            }}
                            placeholder="admin@smcooling.com"
                        />
                        {errors.email && <p style={{ color: '#ef4444', fontSize: '11px', marginTop: '5px' }}>{errors.email}</p>}
                    </div>

                    {/* Password Input */}
                    <div>
                        <label style={{ fontSize: '11px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '8px', display: 'block' }}>รหัสผ่าน</label>
                        <input 
                            type="password" 
                            value={data.password}
                            onChange={e => setData('password', e.target.value)}
                            style={{ 
                                width: '100%', padding: '12px 16px', borderRadius: '12px',
                                border: '1px solid #e2e8f0', background: '#fcfdfe', outline: 'none'
                            }}
                            placeholder="••••••••"
                        />
                        {errors.password && <p style={{ color: '#ef4444', fontSize: '11px', marginTop: '5px' }}>{errors.password}</p>}
                    </div>

                    {/* Submit Button */}
                    <button 
                        type="submit" 
                        disabled={processing}
                        style={{ 
                            background: 'linear-gradient(135deg, #2563eb, #1d4ed8)',
                            color: '#fff', border: 'none', padding: '14px', borderRadius: '14px',
                            fontWeight: 700, fontSize: '13px', cursor: 'pointer', marginTop: '10px',
                            boxShadow: '0 10px 15px -3px rgba(37, 99, 235, 0.2)',
                            transition: 'all 0.2s'
                        }}
                    >
                        {processing ? 'กำลังตรวจสอบ...' : 'เข้าสู่ระบบ'}
                    </button>
                </form>

                <div style={{ textAlign: 'center', marginTop: '30px' }}>
                    <a href="/" style={{ fontSize: '11px', color: '#94a3b8', textDecoration: 'none' }}>
                        ← กลับไปหน้าหลักลูกค้า
                    </a>
                </div>
            </div>
        </div>
    );
}