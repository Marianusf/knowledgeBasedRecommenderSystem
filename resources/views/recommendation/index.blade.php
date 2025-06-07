<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rekomendasi Handphone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showDetail(phone) {
            const detail = `
                <img src="${phone.image_path}" alt="${phone.model_name}" class="w-full max-h-60 object-contain mb-4">
                <strong>Company Name:</strong> ${phone.company_name}<br>
                <strong>Model Name:</strong> ${phone.model_name}<br>
                <strong>Mobile Weight:</strong> ${phone.mobile_weight}<br>
                <strong>RAM:</strong> ${phone.ram} GB<br>
                <strong>Front Camera:</strong> ${phone.front_camera}<br>
                <strong>Back Camera:</strong> ${phone.back_camera}<br>
                <strong>Processor:</strong> ${phone.processor}<br>
                <strong>Battery Capacity:</strong> ${phone.battery_capacity} mAh<br>
                <strong>Screen Size:</strong> ${phone.screen_size} inch<br>
                <strong>Launched Year:</strong> ${phone.launched_year}<br>
                <strong>Price:</strong> Rp ${Number(phone.price).toLocaleString('id-ID')}
            `;
            document.getElementById('modal-body').innerHTML = detail;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-700">Sistem Rekomendasi Handphone</h1>

        <!-- Form Pencarian -->
        <form action="{{ route('recommendation.hybrid') }}" method="GET" class="space-y-5 mb-10">
            <div>
                <label for="max_price" class="block font-semibold mb-1">Harga Maksimal (Rp)</label>
                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                    placeholder="Contoh: 5000000" class="w-full border rounded p-2" />
            </div>
            <div>
                <label for="min_launched_year" class="block font-semibold mb-1">Tahun Rilis Minimal</label>
                <input type="number" name="min_launched_year" id="min_launched_year"
                    value="{{ request('min_launched_year') }}" placeholder="2020" class="w-full border rounded p-2" />
            </div>
            <div>
                <label for="min_ram" class="block font-semibold mb-1">RAM Minimal (GB)</label>
                <input type="number" name="min_ram" id="min_ram" value="{{ request('min_ram') }}" placeholder="4"
                    class="w-full border rounded p-2" />
            </div>
            <div>
                <label for="battery_capacity" class="block font-semibold mb-1">Baterai Minimal (mAh)</label>
                <input type="number" name="battery_capacity" id="battery_capacity"
                    value="{{ request('battery_capacity') }}" placeholder="4000" class="w-full border rounded p-2" />
            </div>
            <div>
                <label for="preferred_brand" class="block font-semibold mb-1">Preferensi Brand (opsional)</label>
                <input type="text" name="preferred_brand" id="preferred_brand"
                    value="{{ request('preferred_brand') }}" placeholder="Contoh: Apple, Samsung"
                    class="w-full border rounded p-2" />
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition duration-300">
                Cari Handphone
            </button>
        </form>

        <!-- Rekomendasi Terbaik -->
        <h2 class="text-xl font-semibold mb-4">Rekomendasi Terbaik Sesuai Kriteria Anda</h2>
        @if ($rankedPhones->isEmpty())
            <p class="text-center text-gray-600">Tidak ada handphone yang sangat cocok dengan semua kriteria Anda.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach ($rankedPhones as $phone)
                    <div class="bg-white rounded shadow p-4 hover:shadow-lg transition-shadow duration-300">
                        <div class="h-40 bg-gray-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                            <img src="{{ $phone->image_path }}" alt="{{ $phone->model_name }}"
                                class="object-contain max-h-full max-w-full" />
                        </div>
                        <h2 class="text-lg font-bold">{{ $phone->model_name }}</h2>
                        <p class="text-gray-700">{{ $phone->company_name }}</p>
                        <p class="text-blue-600 font-semibold">Rp {{ number_format($phone->price, 0, ',', '.') }}</p>
                        <button onclick='showDetail(@json($phone))'
                            class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Lihat Detail
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Alternatif Lain -->
        <hr class="my-8" />
        <h2 class="text-xl font-semibold mb-4">Alternatif Lain yang Mungkin Masih Relevan</h2>
        @if ($allPhones->isEmpty())
            <p class="text-center text-gray-600">Tidak ada handphone lain yang memenuhi sebagian besar kriteria Anda.
            </p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($allPhones as $phone)
                    <div class="bg-white rounded shadow p-4">
                        <div class="h-40 bg-gray-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                            <img src="{{ $phone->image_path }}" alt="{{ $phone->model_name }}"
                                class="object-contain max-h-full max-w-full" />
                        </div>
                        <h2 class="text-lg font-bold">{{ $phone->model_name }}</h2>
                        <p class="text-gray-700">{{ $phone->company_name }}</p>
                        <p class="text-blue-600 font-semibold">Rp {{ number_format($phone->price, 0, ',', '.') }}</p>
                        <button onclick='showDetail(@json($phone))'
                            class="mt-3 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Lihat Detail
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal Detail -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded shadow max-w-lg w-full">
            <h2 class="text-xl font-bold mb-4">Detail Handphone</h2>
            <div id="modal-body" class="text-sm text-gray-700 space-y-2"></div>
            <button onclick="closeModal()"
                class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Tutup</button>
        </div>
    </div>
</body>

</html>
