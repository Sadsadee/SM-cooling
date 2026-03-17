<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะงานซ่อม - SM Cooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen py-10 flex flex-col items-center justify-start">

        <div class="max-w-5xl mx-auto px-4 text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block group transition-all duration-300 transform hover:scale-105">
                <div class="flex justify-center mb-4">
                    <div class="bg-blue-600 rounded-full p-4 shadow-lg group-hover:bg-blue-700 transition-colors">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v18m9-9H3m15.364 6.364l-12.728-12.728m12.728 0L5.272 18.364"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-blue-700">SM Cooling Center</h1>
                <p class="text-gray-600 mt-1 text-lg">ระบบติดตามสถานะงานและแจ้งชำระเงิน</p>
            </a>
            <div class="h-1 w-20 bg-blue-200 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="w-full max-w-md mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                <form action="{{ route('track.search') }}" method="GET" class="space-y-6">

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-3 text-center">เบอร์โทรศัพท์ของคุณ</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                📞
                            </span>
                            <input type="text" name="phone"
                                class="shadow-sm border border-gray-200 rounded-2xl w-full py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xl transition-all text-center tracking-widest"
                                placeholder="08XXXXXXXX" maxlength="10" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                value="{{ request('phone') ?? ($customer->phone ?? '') }}" required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 transition duration-300 transform active:scale-95 text-lg">
                        🔍 ค้นหาประวัติการแจ้งซ่อม
                    </button>
                </form>

                @if(session('error'))
                    <div
                        class="mt-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm rounded-2xl text-center font-bold animate-pulse">
                        🚨 {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}"
                    class="text-gray-400 hover:text-blue-600 transition text-sm flex items-center justify-center gap-2">
                    🏠 กลับสู่หน้าหลัก
                </a>
            </div>
        </div>

    </div>
</body>

</html>