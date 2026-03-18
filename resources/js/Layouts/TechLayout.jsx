import { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { Snowflake, Home, User, Globe, Settings, LogOut, ChevronDown } from 'lucide-react';

export default function TechLayout({ children }) {
    const { url, props } = usePage();
    const [showDropdown, setShowDropdown] = useState(false);

    const user = props.auth?.user || props.user;

    const navLinks = [
        { href: '/tech/dashboard', label: 'หน้าแรก', icon: Home },
    ];

    const handleLogout = () => {
        if (confirm('คุณต้องการออกจากระบบใช่หรือไม่?')) {
            router.post('/logout');
        }
    };

    return (
        <div className="font-sans min-h-screen text-[#08172e] bg-[#f0f4fb]">
            {/* ... (CSS style เหมือนเดิม) ... */}
            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');
                .sm-nl { transition: all .2s; text-decoration: none; }
                .sm-nl-on { background: rgba(26,111,245,.25) !important; color: #7dbfff !important; box-shadow: inset 0 0 0 1px rgba(26,111,245,.2) !important; }
                .sm-nl:hover:not(.sm-nl-on) { background: rgba(255,255,255,.05); color: #fff; }
                .sm-dropdown { position: absolute; top: calc(100% + 10px); right: 0; width: 200px; background: #ffffff !important; border-radius: 20px; padding: 8px; box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important; border: 1px solid #edf2f7; z-index: 9999 !important; display: flex; flex-direction: column; animation: sm-pop 0.2s ease-out; }
                .sm-dropdown-item { padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; color: #4a5568 !important; display: flex; align-items: center; gap: 10px; transition: 0.2s; cursor: pointer; }
                .sm-dropdown-item:hover { background: #f7fafc; color: #1a6ff5 !important; }
                .sm-dropdown-item.logout { color: #e53e3e !important; border-top: 1px solid #f1f5f9; margin-top: 4px; padding-top: 10px; }
                .sm-dropdown-item.logout:hover { background: #fff5f5; }
                @keyframes sm-pop { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
            `}</style>

            <nav className="sticky top-0 z-[1000] w-full flex items-center justify-between px-4 h-[72px] shadow-lg"
                 style={{ background: 'linear-gradient(90deg,#071525 0%,#0b1e36 60%,#091a30 100%)' }}>
                
                <div className="flex items-center shrink-0">
                    <Link href="/tech/dashboard" className="flex items-center gap-2 no-underline">
                        <div className="w-10 h-10 rounded-[14px] flex items-center justify-center shadow-md shrink-0"
                             style={{ background: 'linear-gradient(135deg,#4aa0ff,#1a6ff5)' }}>
                            <Snowflake className="w-6 h-6 text-white" />
                        </div>
                        <div className="hidden sm:block">
                            <div className="text-sm font-extrabold text-white tracking-tight leading-tight">SM Cooling</div>
                            <div className="text-xs font-bold text-[#4a85c8] uppercase tracking-widest leading-tight mt-0.5">Tech Portal</div>
                        </div>
                    </Link>
                </div>

                <div className="flex gap-2 bg-white/5 p-1 rounded-xl border border-white/5 shrink-0 mx-2 overflow-x-auto hide-scrollbar">
                    {navLinks.map(({ href, label, icon: Icon }) => {
                        const active = url?.startsWith(href);
                        return (
                            <Link key={href} href={href} 
                                className={`sm-nl ${active ? 'sm-nl-on' : ''} px-4 py-2 rounded-[10px] text-xs font-bold whitespace-nowrap flex items-center gap-1.5`}
                                style={{ color: active ? '#7dbfff' : '#7a9ec4' }}
                            >
                                <Icon className="w-4 h-4" /> {label}
                            </Link>
                        );
                    })}
                </div>

                <div className="relative shrink-0">
                    <div onClick={() => setShowDropdown(!showDropdown)} className="flex items-center gap-2 cursor-pointer bg-white/5 p-1.5 rounded-full border border-white/10 hover:bg-white/10 transition-all">
                        <div className="w-8 h-8 rounded-full flex items-center justify-center bg-blue-100 border-2 border-white/20 shrink-0 shadow-inner">
                            <User className="w-4 h-4 text-blue-600" />
                        </div>
                        <span className="hidden sm:block text-sm font-bold text-[#e2e8f0] truncate max-w-[100px]">
                            {user?.name || '...'}
                        </span>
                        <ChevronDown className={`w-3 h-3 text-[#4a6a8a] mx-1 transition-transform duration-300 ${showDropdown ? 'rotate-180' : ''}`} />
                    </div>

                    {showDropdown && (
                        <div className="sm-dropdown" onMouseLeave={() => setShowDropdown(false)}>
                            <div className="sm-dropdown-item logout" onClick={handleLogout}><LogOut className="w-4 h-4" /> ออกจากระบบ</div>
                        </div>
                    )}
                </div>
            </nav>

            <main style={{ padding: '32px 16px 56px', maxWidth: '1300px', margin: '0 auto' }}>
                {children}
            </main>
        </div>
    );
}