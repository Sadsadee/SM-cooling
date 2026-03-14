<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะงานซ่อม - SM Cooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-6">
        <div class="text-center mb-8">
            <div
                class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl shadow-blue-200">
                <span class="text-3xl text-white">❄️</span>
            </div>
            <h1 class="text-2xl font-black text-gray-800">SM Cooling Center</h1>
            <p class="text-gray-500 text-sm mt-1">ระบบติดตามสถานะงานและแจ้งชำระเงิน</p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
            <form action="{{ route('track.search') }}" method="GET">

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2 text-center">เบอร์โทรศัพท์ของคุณ</label>
                    <input type="text" name="phone" placeholder="เช่น 0812345678" required
                        class="w-full border-gray-300 rounded-2xl py-3 px-4 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 text-center text-xl font-bold tracking-widest bg-gray-50 transition">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition">
                    🔍 ค้นหาประวัติการแจ้งซ่อม
                </button>
            </form>

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl text-center font-bold">
                    🚨 {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>