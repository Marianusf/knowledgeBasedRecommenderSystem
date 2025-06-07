<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>{{ isset($phone) ? 'Edit Data HP' : 'Tambah HP Baru' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-100 transition duration-300">
    <!-- Toggle Mode -->
    <div class="flex justify-end p-4">
        <button onclick="toggleDarkMode()"
            class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded shadow">
            Toggle Mode
        </button>
    </div>

    <!-- Form Card -->
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">
            {{ isset($phone) ? 'Edit Data HP' : 'Tambah HP Baru' }}
        </h1>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6'
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#d33'
                });
            </script>
        @endif

        <form method="POST"
            action="{{ isset($phone) ? route('admin.phones.update', $phone) : route('admin.phones.store') }}"
            enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6 needs-validation" novalidate>
            @csrf
            @if (isset($phone))
                @method('PUT')
            @endif

            @php
                $fields = [
                    [
                        'name' => 'company_name',
                        'label' => 'Brand',
                        'type' => 'text',
                        'placeholder' => 'Contoh: Samsung',
                    ],
                    [
                        'name' => 'model_name',
                        'label' => 'Model',
                        'type' => 'text',
                        'placeholder' => 'Contoh: Galaxy A52',
                    ],
                    [
                        'name' => 'mobile_weight',
                        'label' => 'Berat HP (gram)',
                        'type' => 'number',
                        'placeholder' => 'Contoh: 189',
                    ],
                    ['name' => 'ram', 'label' => 'RAM (GB)', 'type' => 'number', 'placeholder' => 'Contoh: 6'],
                    [
                        'name' => 'front_camera',
                        'label' => 'Kamera Depan',
                        'type' => 'text',
                        'placeholder' => 'Contoh: 32 MP',
                    ],
                    [
                        'name' => 'back_camera',
                        'label' => 'Kamera Belakang',
                        'type' => 'text',
                        'placeholder' => 'Contoh: 64+12+5+5 MP',
                    ],
                    [
                        'name' => 'processor',
                        'label' => 'Processor',
                        'type' => 'text',
                        'placeholder' => 'Contoh: Snapdragon 720G',
                    ],
                    [
                        'name' => 'battery_capacity',
                        'label' => 'Baterai (mAh)',
                        'type' => 'number',
                        'placeholder' => 'Contoh: 4500',
                    ],
                    [
                        'name' => 'screen_size',
                        'label' => 'Ukuran Layar (inch)',
                        'type' => 'text',
                        'placeholder' => 'Contoh: 6.5',
                    ],
                    [
                        'name' => 'launched_year',
                        'label' => 'Tahun Rilis',
                        'type' => 'number',
                        'placeholder' => 'Contoh: 2022',
                    ],
                    [
                        'name' => 'price',
                        'label' => 'Harga (Rp)',
                        'type' => 'number',
                        'placeholder' => 'Contoh: 3500000',
                    ],
                ];
            @endphp

            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}"
                        class="block text-sm font-medium mb-1">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                        value="{{ old($field['name'], $phone->{$field['name']} ?? '') }}"
                        placeholder="{{ $field['placeholder'] }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-400 outline-none"
                        required>
                </div>
            @endforeach

            <!-- Upload Gambar -->
            <div class="col-span-1 md:col-span-2">
                <label for="image" class="block text-sm font-medium mb-1">Gambar (opsional)</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                    class="block w-full border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-lg bg-white dark:bg-gray-700 dark:text-white">

                <div class="mt-4">
                    <p class="text-sm mb-1 text-gray-600 dark:text-gray-300">Preview:</p>
                    @if (isset($phone) && $phone->image_path)
                        <img id="oldImage" src="{{ asset($phone->image_path) }}" class="w-40 rounded shadow mb-2">
                    @endif
                    <img id="preview" class="w-40 rounded shadow hidden mb-2">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-span-1 md:col-span-2">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200">
                    {{ isset($phone) ? 'Update Data' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const oldImage = document.getElementById('oldImage');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (oldImage) {
                        oldImage.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
