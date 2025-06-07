<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        fade: 'fadeIn 0.5s ease-in-out',
                        shake: 'shake 0.3s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: 0
                            },
                            '100%': {
                                opacity: 1
                            },
                        },
                        shake: {
                            '0%, 100%': {
                                transform: 'translateX(0)'
                            },
                            '25%': {
                                transform: 'translateX(-4px)'
                            },
                            '75%': {
                                transform: 'translateX(4px)'
                            },
                        },
                    },
                }
            }
        };
    </script>
</head>

<body class="h-full bg-gray-100 dark:bg-gray-900 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 animate-fade transition-all">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Admin Login</h2>
            <p class="text-gray-500 dark:text-gray-300 text-sm">Silahkan Masukan data Login Admin</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-shake">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    required autofocus placeholder="admin@example.com">
            </div>

            <div>
                <label for="password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    required placeholder="********">
            </div>

            <div class="flex justify-between items-center text-sm">
                <label class="inline-flex items-center dark:text-gray-200">
                    <input type="checkbox" class="form-checkbox text-blue-600" onclick="toggleDarkMode()">
                    <span class="ml-2">Dark Mode</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                Login
            </button>
        </form>
    </div>

    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
    </script>
</body>

</html>
