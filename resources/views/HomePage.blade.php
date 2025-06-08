<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beranda | Rekomendasi Handphone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        // Fungsi untuk toggle dark mode
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white min-h-screen flex flex-col justify-center items-center p-6 transition-colors duration-500">

    <!-- Tombol Toggle -->
    <button onclick="toggleDarkMode()"
        class="fixed top-4 right-4 px-4 py-2 text-sm font-medium bg-white dark:bg-gray-800 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-700 rounded-full shadow-lg hover:scale-105 transition-all duration-300 z-50">
        🌓 Ganti Mode
    </button>

    <div class="text-center max-w-2xl mt-20 md:mt-0">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-6 text-blue-700 dark:text-blue-300 leading-tight">
            Sistem Rekomendasi Handphone
        </h1>
        <p class="text-lg mb-10 text-gray-700 dark:text-gray-300">
            Temukan handphone terbaik berdasarkan kebutuhan dan preferensimu. Sistem ini memberikan rekomendasi cerdas
            berdasarkan spesifikasi, harga, dan performa.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ route('recommendation.hybrid') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow-lg transition-transform transform hover:scale-105 duration-300">
                🔍 Cari Rekomendasi HP
            </a>

            <a href="{{ route('admin.login') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow-lg transition-transform transform hover:scale-105 duration-300">
                🔐 Login Admin
            </a>
        </div>
    </div>

    <footer class="mt-20 text-sm text-gray-500 dark:text-gray-400 text-center">
        &copy; {{ now()->year }} Sistem Rekomendasi HP. Dibuat dengan ❤️ dan Laravel.
    </footer>
</body>

</html>
