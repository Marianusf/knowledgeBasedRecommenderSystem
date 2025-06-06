<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rekomendasi Handphone</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <label for="processor" class="block font-semibold mb-1">Processor (opsional)</label>
                <input type="text" name="processor" id="processor" value="{{ request('processor') }}"
                    placeholder="Snapdragon" class="w-full border rounded p-2" />
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition duration-300">
                Cari Handphone
            </button>
        </form>

        <!-- Daftar Rekomendasi Gabungan -->

        @if ($rankedPhones->isEmpty())
            <p class="text-center text-gray-600">Tidak ada handphone yang cocok dengan kriteria yang Anda pilih. Silakan
                coba ubah filter pencarian.</p>
        @else
            <h2 class="text-xl font-semibold mb-4">Rekomendasi Terbaik Sesuai Pilihan Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach ($rankedPhones as $phone)
                    <div class="bg-white rounded shadow p-4 flex flex-col">
                        <div class="h-40 bg-gray-200 rounded mb-4 flex items-center justify-center overflow-hidden">
                            <img src="{{ $phone->image_path }}"
                                alt="{{ $phone->company_name }} {{ $phone->model_name }}"
                                class="object-contain max-h-full max-w-full" />
                        </div>
                        <h2 class="text-xl font-semibold mb-2">{{ $phone->company_name }} {{ $phone->model_name }}</h2>
                        <p><strong>Tahun Rilis:</strong> {{ $phone->launched_year }}</p>
                        <p><strong>Harga:</strong> Rp {{ number_format($phone->price, 0, ',', '.') }}</p>
                        <p><strong>RAM:</strong> {{ $phone->ram }} GB</p>
                        <p><strong>Prosesor:</strong> {{ $phone->processor }}</p>
                        <p><strong>Baterai:</strong> {{ $phone->battery_capacity }} mAh</p>
                        <p><strong>Berat:</strong> {{ $phone->mobile_weight }} gram</p>
                        <p><strong>Kecocokan:</strong> {{ round($phone->similarity_score * 100, 0) }}%</p>
                    </div>
                @endforeach
            </div>
        @endif

        <hr class="my-8" />

        <h2 class="text-xl font-semibold mb-4">Alternatif Lain yang Mungkin Cocok untuk Anda</h2>

        @if ($allPhones->isEmpty())
            <p class="text-center text-gray-600">Tidak ada handphone lain yang tersedia saat ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($allPhones as $phone)
                    <div class="bg-white rounded shadow p-4 flex flex-col">
                        <div class="h-40 bg-gray-200 rounded mb-4 flex items-center justify-center overflow-hidden">
                            <img src="{{ $phone->image_path }}"
                                alt="{{ $phone->company_name }} {{ $phone->model_name }}"
                                class="object-contain max-h-full max-w-full" />
                        </div>
                        <h2 class="text-xl font-semibold mb-2">{{ $phone->company_name }} {{ $phone->model_name }}
                        </h2>
                        <p><strong>Tahun Rilis:</strong> {{ $phone->launched_year }}</p>
                        <p><strong>Harga:</strong> Rp {{ number_format($phone->price, 0, ',', '.') }}</p>
                        <p><strong>RAM:</strong> {{ $phone->ram }} GB</p>
                        <p><strong>Prosesor:</strong> {{ $phone->processor }}</p>
                        <p><strong>Baterai:</strong> {{ $phone->battery_capacity }} mAh</p>
                        <p><strong>Berat:</strong> {{ $phone->mobile_weight }} gram</p>
                        <p><strong>Kecocokan:</strong> {{ round($phone->similarity_score * 100, 0) }}%</p>
                    </div>
                @endforeach
            </div>
        @endif


    </div>
</body>

</html>
