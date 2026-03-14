<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📦 บริการและคลังอะไหล่</h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ส่วนที่ 1: คลังอะไหล่ --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-white flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-800 flex items-center">
                        <span class="p-2 bg-orange-100 rounded-lg mr-3">⚙️</span> คลังอะไหล่
                    </h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <th class="px-4 py-2 italic text-orange-500">✨ เพิ่มอะไหล่ใหม่</th>
                                <th class="px-4 py-2">ราคา</th>
                                <th class="px-4 py-2">สต็อก</th>
                                <th class="px-4 py-2 text-center">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr class="bg-orange-50/50">
                                <form action="{{ route('admin.inventory.storePart') }}" method="POST">
                                    @csrf
                                    <td class="px-4 py-4"><input type="text" name="part_name"
                                            placeholder="ชื่ออะไหล่ใหม่..."
                                            class="border-orange-200 rounded-xl text-sm w-full bg-white"></td>
                                    <td class="px-4 py-4"><input type="number" name="price" placeholder="ราคา"
                                            class="border-orange-200 rounded-xl text-sm w-full bg-white"></td>
                                    <td class="px-4 py-4"><input type="number" name="stock" placeholder="จำนวน"
                                            class="border-orange-200 rounded-xl text-sm w-full bg-white"></td>
                                    <td class="px-4 py-4 text-center">
                                        <button type="submit"
                                            class="bg-orange-600 text-white px-6 py-2 rounded-xl text-xs font-black shadow-md hover:bg-orange-700 transition">+
                                            เพิ่มลงคลัง</button>
                                    </td>
                                </form>
                            </tr>
                            @foreach($parts as $part)
                                <tr class="hover:bg-gray-50 transition">
                                    <form action="{{ route('admin.inventory.updatePart', $part->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <td class="px-4 py-4"><input type="text" name="part_name"
                                                value="{{ $part->part_name }}"
                                                class="border-transparent hover:border-gray-200 focus:border-orange-500 rounded-xl text-sm w-full bg-transparent focus:bg-white transition">
                                        </td>
                                        <td class="px-4 py-4"><input type="number" name="price" value="{{ $part->price }}"
                                                class="border-transparent hover:border-gray-200 focus:border-orange-500 rounded-xl text-sm w-full bg-transparent focus:bg-white transition">
                                        </td>
                                        <td class="px-4 py-4"><input type="number" name="stock" value="{{ $part->stock }}"
                                                class="border-transparent hover:border-gray-200 focus:border-orange-500 rounded-xl text-sm w-full bg-transparent focus:bg-white transition">
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <button type="submit"
                                                class="text-orange-500 font-bold text-xs hover:underline">อัปเดต</button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ส่วนที่ 2: อัตราค่าบริการ --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-white">
                    <h3 class="text-xl font-black text-gray-800 flex items-center">
                        <span class="p-2 bg-blue-100 rounded-lg mr-3">💧</span> อัตราค่าบริการหลัก
                    </h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <th class="px-4 py-2 italic text-blue-500">✨ เพิ่มบริการใหม่</th>
                                <th class="px-4 py-2">ค่าบริการ (บาท)</th>
                                <th class="px-4 py-2 text-center">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr class="bg-blue-50/50">
                                <form action="{{ route('admin.inventory.storeService') }}" method="POST">
                                    @csrf
                                    <td class="px-4 py-4"><input type="text" name="service_name"
                                            placeholder="ชื่อประเภทบริการใหม่..."
                                            class="border-blue-200 rounded-xl text-sm w-full bg-white"></td>
                                    <td class="px-4 py-4"><input type="number" name="base_price"
                                            placeholder="ระบุราคาค่าแรง"
                                            class="border-blue-200 rounded-xl text-sm w-full bg-white"></td>
                                    <td class="px-4 py-4 text-center">
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-6 py-2 rounded-xl text-xs font-black shadow-md hover:bg-blue-700 transition">+
                                            เพิ่มบริการ</button>
                                    </td>
                                </form>
                            </tr>
                            @foreach($services as $service)
                                <tr class="hover:bg-gray-50 transition">
                                    <form action="{{ route('admin.inventory.updateService', $service->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <td class="px-4 py-4"><input type="text" name="service_name"
                                                value="{{ $service->service_name }}"
                                                class="border-transparent hover:border-gray-200 focus:border-blue-500 rounded-xl text-sm w-full bg-transparent focus:bg-white transition">
                                        </td>
                                        <td class="px-4 py-4"><input type="number" name="base_price"
                                                value="{{ $service->base_price }}"
                                                class="border-transparent hover:border-gray-200 focus:border-blue-500 rounded-xl text-sm w-full bg-transparent focus:bg-white transition">
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <button type="submit"
                                                class="text-blue-500 font-bold text-xs hover:underline">อัปเดตราคา</button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>