<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SM Cooling Center - บริการซ่อมแอร์ ล้างแอร์ครบวงจร</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="antialiased text-gray-800 hero-pattern min-h-screen flex flex-col">

    <nav class="bg-white/80 backdrop-blur-md shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <span class="text-3xl mr-2">❄️</span>
                    <span class="font-black text-2xl text-blue-800 tracking-tighter">SM COOLING</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-600 hover:text-blue-600 transition">เข้าสู่ระบบหลังบ้าน</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition">สำหรับพนักงาน</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-28 w-full">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-lg animate-bounce" role="alert">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">✅</span>
                    <div>
                        <p class="font-bold text-lg leading-tight">สำเร็จ!</p>
                        <p class="text-sm opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <main class="flex-grow flex items-center justify-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            
            <div class="inline-block bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full font-bold text-sm mb-6 border border-blue-200">
                👨‍🔧 บริการรวดเร็ว จริงใจ ได้มาตรฐาน
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-gray-900 mb-6 leading-tight">
                แอร์ไม่เย็น น้ำหยด <br>
                <span class="text-blue-600">เรียกช่าง SM Cooling</span>
            </h1>
            
            <p class="mt-4 text-xl text-gray-600 max-w-2xl mx-auto mb-10">
                บริการล้าง ซ่อม ย้าย ติดตั้งเครื่องปรับอากาศทุกชนิด โดยทีมช่างมืออาชีพ ประเมินราคาก่อนซ่อม พร้อมรับประกันงาน
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('request.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg py-4 px-10 rounded-full shadow-xl shadow-blue-200 transition transform hover:-translate-y-1 flex items-center justify-center">
                    <span class="text-2xl mr-2">📝</span> แจ้งซ่อมแอร์
                </a>
                
                <a href="{{ route('track.index') }}" class="bg-white hover:bg-gray-50 text-gray-800 font-bold text-lg py-4 px-10 rounded-full shadow-lg border border-gray-200 transition transform hover:-translate-y-1 flex items-center justify-center">
                    <span class="text-2xl mr-2">🔍</span> ติดตามสถานะงาน
                </a>
            </div>

            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6 text-center max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-4xl mb-3">💦</div>
                    <h3 class="font-bold text-gray-800">ล้างแอร์</h3>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-4xl mb-3">🔧</div>
                    <h3 class="font-bold text-gray-800">ซ่อมแอร์</h3>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-4xl mb-3">⚡</div>
                    <h3 class="font-bold text-gray-800">เติมน้ำยา</h3>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-4xl mb-3">📦</div>
                    <h3 class="font-bold text-gray-800">ติดตั้งใหม่</h3>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 text-center mt-auto">
        <p class="text-gray-500 text-sm font-medium">
            &copy; {{ date('Y') }} SM Cooling Center. ระบบจัดการคิวงานช่างแอร์ครบวงจร
        </p>
    </footer>

</body>
</html>