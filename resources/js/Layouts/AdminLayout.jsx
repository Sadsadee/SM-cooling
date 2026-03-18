import { useState, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { Snowflake, LayoutDashboard, Package, Wrench, Users, Home, Globe, Settings, LogOut, ChevronDown } from 'lucide-react';

export default function AdminLayout({ children }) {
    const { url, props } = usePage();
    const [showDropdown, setShowDropdown] = useState(false);

    const user = props.auth?.user || props.user;
    const role = user?.role;
    const isAdmin = role === 'admin';

    useEffect(() => {
        console.log("Layout Props:", props);
    }, [props]);

    // 🔗 ใช้ Icon component แทนอิโมจิ
    const navLinks = isAdmin ? [
        { href: '/admin/requests', label: 'Dashboard', icon: LayoutDashboard },
        { href: '/admin/inventory', label: 'คลังอะไหล่', icon: Package },
        { href: '/admin/techs', label: 'จัดการช่าง', icon: Wrench },
        { href: '/admin/users', label: 'จัดการลูกค้า', icon: Users },
    ] : [
        { href: '/tech/dashboard', label: 'หน้าแรกงาน', icon: Home },
    ];

    const handleLogout = () => {
        if (confirm('คุณต้องการออกจากระบบใช่หรือไม่?')) {
            router.post('/logout');
        }
    };

    return (
        <div style={{ fontFamily: "'Sora', sans-serif", background: '#f0f4fb', minHeight: '100vh', color: '#08172e' }}>
            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');
                
                .sm-nl { transition: all .2s; text-decoration: none; }
                .sm-nl-on { background: rgba(26,111,245,.25) !important; color: #7dbfff !important; box-shadow: inset 0 0 0 1px rgba(26,111,245,.2) !important; }
                .sm-nl:hover:not(.sm-nl-on) { background: rgba(255,255,255,.05); color: #fff; }

                .sm-dropdown {
                    position: absolute;
                    top: calc(100% + 10px);
                    right: 0;
                    width: 220px;
                    background: #ffffff !important;
                    border-radius: 20px;
                    padding: 10px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
                    border: 1px solid #edf2f7;
                    z-index: 9999 !important;
                    display: flex;
                    flex-direction: column;
                    animation: sm-pop 0.2s ease-out;
                }
                .sm-dropdown-item {
                    padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #4a5568 !important;
                    text-decoration: none; display: flex; align-items: center; gap: 10px; transition: 0.2s; cursor: pointer;
                }
                .sm-dropdown-item:hover { background: #f7fafc; color: #1a6ff5 !important; }
                .sm-dropdown-item.logout { color: #e53e3e !important; border-top: 1px solid #f1f5f9; margin-top: 5px; padding-top: 12px; }
                .sm-dropdown-item.logout:hover { background: #fff5f5; }
                @keyframes sm-pop { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
            `}</style>

            <nav style={{
                background: 'linear-gradient(90deg,#071525 0%,#0b1e36 60%,#091a30 100%)',
                height: '72px', display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                padding: '0 32px', position: 'sticky', top: 0, zIndex: 1000,
                boxShadow: '0 4px 20px rgba(0,0,0,0.25)',
            }}>
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <Link href="/dashboard" style={{ textDecoration: 'none', display: 'flex', alignItems: 'center', gap: '14px' }}>
                        <div style={{
                            width: '42px', height: '42px', borderRadius: '14px',
                            background: 'linear-gradient(135deg,#4aa0ff,#1a6ff5)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            boxShadow: '0 4px 12px rgba(26,111,245,0.4)'
                        }}>
                            <Snowflake className="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <div style={{ fontSize: '18px', fontWeight: 800, color: '#fff', letterSpacing: '-0.5px' }}>SM Cooling</div>
                            <div style={{ fontSize: '10px', fontWeight: 700, color: '#4a85c8', textTransform: 'uppercase', letterSpacing: '1px' }}>
                                {isAdmin ? 'Admin Portal' : 'Tech Portal'}
                            </div>
                        </div>
                    </Link>
                </div>

                <div style={{ display: 'flex', gap: '4px', background: 'rgba(255,255,255,.05)', padding: '5px', borderRadius: '15px', border: '1px solid rgba(255,255,255,.05)' }}>
                    {navLinks.map(({ href, label, icon: Icon }) => {
                        const active = url.startsWith(href);
                        return (
                            <Link key={href} href={href}
                                className={`sm-nl ${active ? 'sm-nl-on' : ''}`}
                                style={{ 
                                    padding: '8px 20px', borderRadius: '11px', fontSize: '13.5px', fontWeight: 600, 
                                    color: active ? '#7dbfff' : '#7a9ec4', display: 'flex', alignItems: 'center', gap: '8px' 
                                }}
                            >
                                <Icon className="w-4 h-4" /> {label}
                            </Link>
                        );
                    })}
                </div>

                <div style={{ position: 'relative' }}>
                    <div onClick={() => setShowDropdown(!showDropdown)} style={{
                            display: 'flex', alignItems: 'center', gap: '10px', cursor: 'pointer',
                            background: 'rgba(255,255,255,.06)', padding: '6px 16px 6px 8px', borderRadius: '35px',
                            border: '1px solid rgba(255,255,255,.1)', transition: '0.2s'
                        }}>
                        <div style={{
                            width: '34px', height: '34px', borderRadius: '50%',
                            background: 'linear-gradient(135deg,#4aa0ff,#1a6ff5)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: '14px', fontWeight: 800, color: '#fff',
                            border: '2px solid rgba(255,255,255,0.2)'
                        }}>
                            {user?.name ? user.name.charAt(0) : '?'}
                        </div>
                        <span style={{ fontSize: '13px', fontWeight: 700, color: '#e2e8f0' }}>
                            {user?.name || 'กำลังโหลด...'}
                        </span>
                        <ChevronDown className={`w-3 h-3 text-[#4a6a8a] transition-transform duration-300 ${showDropdown ? 'rotate-180' : ''}`} />
                    </div>

                    {showDropdown && (
                        <div className="sm-dropdown" onMouseLeave={() => setShowDropdown(false)}>
                            <Link href="/" className="sm-dropdown-item"><Globe className="w-4 h-4" /> หน้าเว็บลูกค้า</Link>
                            <Link href="/profile" className="sm-dropdown-item"><Settings className="w-4 h-4" /> ตั้งค่าโปรไฟล์</Link>
                            <div className="sm-dropdown-item logout" onClick={handleLogout}><LogOut className="w-4 h-4" /> ออกจากระบบ</div>
                        </div>
                    )}
                </div>
            </nav>

            <main style={{ padding: '32px 36px 56px', maxWidth: '1300px', margin: '0 auto' }}>
                {children}
            </main>
        </div>
    );
}