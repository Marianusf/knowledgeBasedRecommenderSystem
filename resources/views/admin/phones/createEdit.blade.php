<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ isset($phone) ? 'Edit Data HP' : 'Tambah HP Baru' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center p-6">

    <form method="POST" action="{{ isset($phone) ? route('admin.phones.update', $phone) : route('admin.phones.store') }}"
        enctype="multipart/form-data"
        class="w-full max-w-xl bg-white dark:bg-gray-800 shadow-xl rounded-xl px-8 pt-6 pb-8 space-y-6 transition-all duration-300">

        @csrf
        @if (isset($phone))
            @method('PUT')
        @endif

        <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white text-center">
            {{ isset($phone) ? 'Edit Data HP' : 'Tambah HP Baru' }}
        </h2>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                <strong class="font-semibold">Oops!</strong> {{ $errors->first() }}
            </div>
        @endif

        {{-- Model --}}
        <div>
            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Model</label>
            <input type="text" name="model" id="model" value="{{ old('model', $phone->model ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: iPhone 14 Pro Max">
        </div>

        {{-- Brand --}}
        <div>
            <label for="company_name"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Brand</label>
            <input type="text" name="company_name" id="company_name"
                value="{{ old('company_name', $phone->company_name ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: Apple">
        </div>

        {{-- Harga --}}
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Harga
                (Rp)</label>
            <input type="number" name="price" id="price" value="{{ old('price', $phone->price ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: 15000000">
        </div>

        {{-- Tahun Rilis --}}
        <div>
            <label for="launched_year" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tahun
                Rilis</label>
            <input type="number" name="launched_year" id="launched_year"
                value="{{ old('launched_year', $phone->launched_year ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: 2023">
        </div>

        {{-- RAM --}}
        <div>
            <label for="ram" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">RAM
                (GB)</label>
            <input type="number" name="ram" id="ram" value="{{ old('ram', $phone->ram ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: 6">
        </div>

        {{-- Baterai --}}
        <div>
            <label for="battery_capacity"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Kapasitas Baterai (mAh)</label>
            <input type="number" name="battery_capacity" id="battery_capacity"
                value="{{ old('battery_capacity', $phone->battery_capacity ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600"
                required placeholder="Contoh: 5000">
        </div>

        {{-- Gambar --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Gambar
                (opsional)</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-400 focus:outline-none">

            @if (isset($phone) && $phone->image_path)
                <div class="mt-3">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-1">Gambar Saat Ini:</p>
                    <img src="{{ asset($phone->image_path) }}" alt="Gambar HP"
                        class="w-36 rounded-lg shadow-md border dark:border-gray-700">
                </div>
            @endif
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-4">
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105">
                {{ isset($phone) ? 'Update Data' : 'Simpan' }}
            </button>
        </div>
    </form>
</body>

</html>
