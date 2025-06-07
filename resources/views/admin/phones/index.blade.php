<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data HP - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen text-gray-800">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Daftar HP</h1>
            <a href="{{ route('admin.phones.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Tambah HP
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('admin.phones.index') }}" class="mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari model atau brand..."
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-400">
        </form>

        <!-- Cards -->
        @if ($phones->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($phones as $hp)
                    @php
                        $company = strtolower($hp->company_name);
                        $jpgPath = asset("storage/images/phone/{$company}.jpg");
                        $jpegPath = asset("storage/images/phone/{$company}.jpeg");

                        $publicJpgPath = public_path("storage/images/phone/{$company}.jpg");
                        $publicJpegPath = public_path("storage/images/phone/{$company}.jpeg");

                        if (file_exists($publicJpgPath)) {
                            $imgUrl = $jpgPath;
                        } elseif (file_exists($publicJpegPath)) {
                            $imgUrl = $jpegPath;
                        } else {
                            $imgUrl = asset('storage/images/phone/default.png');
                        }
                    @endphp

                    <div x-data="{ show: false }" class="bg-white p-4 rounded-lg shadow border flex flex-col space-y-3">
                        <img src="{{ $imgUrl }}" alt="{{ $hp->company_name }}"
                            class="w-full h-40 object-cover rounded-md shadow-sm">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $hp->company_name }}</h2>
                            <p class="text-gray-700 font-medium">Rp{{ number_format($hp->price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex justify-between items-center mt-2 space-x-2">
                            <button onclick="showDetail(this)"
                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition"
                                data-company="{{ $hp->company_name }}" data-model="{{ $hp->model_name }}"
                                data-weight="{{ $hp->mobile_weight }}" data-ram="{{ $hp->ram }}"
                                data-front="{{ $hp->front_camera }}" data-back="{{ $hp->back_camera }}"
                                data-processor="{{ $hp->processor }}" data-battery="{{ $hp->battery_capacity }}"
                                data-screen="{{ $hp->screen_size }}" data-year="{{ $hp->launched_year }}"
                                data-price="Rp{{ number_format($hp->price, 0, ',', '.') }}">
                                Detail
                            </button>

                            <a href="{{ route('admin.phones.edit', $hp) }}"
                                class="text-blue-600 hover:underline text-sm">Edit</a>

                            <form action="{{ route('admin.phones.destroy', $hp) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $phones->withQueryString()->links() }}
            </div>
        @else
            <p class="text-center text-gray-500 mt-10">Tidak ada data HP ditemukan.</p>
        @endif
    </div>

    <!-- Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg max-w-lg w-full relative shadow-lg animate-fadeIn">
            <button onclick="closeModal()"
                class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-4" id="modalCompany"></h2>

            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Model:</strong> <span id="modalModel"></span></p>
                <p><strong>Berat:</strong> <span id="modalWeight"></span> gram</p>
                <p><strong>RAM:</strong> <span id="modalRam"></span> GB</p>
                <p><strong>Kamera Depan:</strong> <span id="modalFront"></span></p>
                <p><strong>Kamera Belakang:</strong> <span id="modalBack"></span></p>
                <p><strong>Prosesor:</strong> <span id="modalProcessor"></span></p>
                <p><strong>Baterai:</strong> <span id="modalBattery"></span> mAh</p>
                <p><strong>Ukuran Layar:</strong> <span id="modalScreen"></span> inci</p>
                <p><strong>Tahun Rilis:</strong> <span id="modalYear"></span></p>
                <p><strong>Harga:</strong> <span id="modalPrice"></span></p>
            </div>
        </div>
    </div>

    <!-- Modal Script -->
    <script>
        function showDetail(button) {
            document.getElementById('modalCompany').innerText = button.dataset.company;
            document.getElementById('modalModel').innerText = button.dataset.model;
            document.getElementById('modalWeight').innerText = button.dataset.weight;
            document.getElementById('modalRam').innerText = button.dataset.ram;
            document.getElementById('modalFront').innerText = button.dataset.front;
            document.getElementById('modalBack').innerText = button.dataset.back;
            document.getElementById('modalProcessor').innerText = button.dataset.processor;
            document.getElementById('modalBattery').innerText = button.dataset.battery;
            document.getElementById('modalScreen').innerText = button.dataset.screen;
            document.getElementById('modalYear').innerText = button.dataset.year;
            document.getElementById('modalPrice').innerText = button.dataset.price;

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }
    </script>

    <!-- Animasi modal -->
    <style>
        @keyframes fadeIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</body>

</html>
