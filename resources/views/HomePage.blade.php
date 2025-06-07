<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beranda | Rekomendasi Handphone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Toggle dark mode
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        // Load theme on startup
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white min-h-screen flex flex-col justify-center items-center p-6 transition-colors duration-500">

    <!-- Toggle button -->
    <button onclick="toggleDarkMode()"
        class="absolute top-4 right-4 px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 rounded-full shadow hover:scale-105 transition">
        🌓 Toggle Mode
    </button>

    <div class="text-center max-w-2xl">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-blue-700 dark:text-blue-300">
            Sistem Rekomendasi Handphone
        </h1>
        <p class="text-lg mb-8 text-gray-700 dark:text-gray-300">
            Temukan handphone terbaik berdasarkan kebutuhan dan preferensimu. Sistem ini memberikan rekomendasi cerdas
            berdasarkan spesifikasi, harga, dan performa.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ route('recommendation.hybrid') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-medium shadow-md transition-transform transform hover:scale-105">
                🔍 Cari Rekomendasi HP
            </a>

            <a href="{{ route('admin.login') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium shadow-md transition-transform transform hover:scale-105">
                🔐 Login Admin
            </a>
        </div>
    </div>

    <!-- Optional footer -->
    <footer class="mt-16 text-sm text-gray-500 dark:text-gray-400 text-center">
        &copy; {{ now()->year }} Sistem Rekomendasi HP. Dibuat dengan ❤️ dan Laravel.
    </footer>
</body>

</html>
