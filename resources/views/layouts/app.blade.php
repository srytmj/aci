<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dinamis Page Title --}}
    <title>@yield('title') | SIM Konstruksi</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --main-color: #4f46e5;
        }

        .bg-custom {
            background-color: var(--main-color) !important;
        }

        .text-custom {
            color: var(--main-color) !important;
        }

        .border-custom {
            border-color: var(--main-color) !important;
        }

        /* Scrollbar biar cantik */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
        }
    </style>

    <script>
        // Init Theme & Dark Mode
        (function() {
            const savedColor = localStorage.getItem('theme-color') || '#4f46e5';
            const isDark = localStorage.getItem('dark-mode') === 'true';
            document.documentElement.style.setProperty('--main-color', savedColor);
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>

    {{-- DataTables & SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex">

        <aside
            class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden sm:block fixed h-full transition-colors duration-300 z-50">
            @include('layouts.navigation') {{-- Ini file sidebar.blade.php lo --}}
        </aside>

        <div class="flex-1 sm:ml-64 flex flex-col min-w-0">

            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sm:hidden">
                @include('layouts.navigation')
            </nav>

            <header class="bg-white dark:bg-gray-800 shadow-sm transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                            @yield('title', 'Dashboard')
                        </h1>
                        {{-- Breadcrumbs sederhana --}}
                        <p class="text-xs text-gray-400 mt-1">
                            <a href="{{ route('dashboard') }}" class="hover:text-custom">Main</a> / @yield('title')
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="toggleDarkMode()"
                            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-custom transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <main class="p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Script untuk Flash Message SweetAlert2 --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: 'var(--main-color)',
            });
        </script>
    @endif

    {{-- Error handling sesuai instruksi user --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Waduh!',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#ef4444',
            });
        </script>
    @endif
</body>

</html>
