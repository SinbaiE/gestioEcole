<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotelier') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .pattern-bg {
            background-image: url("data:image/svg+xml,%3Csvg width='6' height='6' viewBox='0 0 6 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%239C92AC' fill-opacity='0.1' fill-rule='evenodd'%3E%3Cpath d='M5 0h1L0 6V5zM6 5v1H5z'/%3E%3C/g%3E%3C/svg%3E");
        }
        .focus-ring {
            @apply focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800;
        }
        .btn {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus-ring transition ease-in-out duration-150;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-100 dark:bg-gray-800 pattern-bg">
        <!-- Sidebar -->
        <aside x-data="{ open: true }" :class="{'w-64': open, 'w-20': !open}" class="flex-shrink-0 w-64 bg-white dark:bg-gray-900/80 border-r border-gray-200 dark:border-gray-700/50 backdrop-blur-sm transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between h-16 px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800 dark:text-white" :class="{'hidden': !open}">
                    Hotelier
                </a>
                <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <nav class="mt-4 px-2 space-y-1">
                <x-sidebar-menu-item route="dashboard" icon="home">Dashboard</x-sidebar-menu-item>
                <x-sidebar-menu-item route="reservations.index" icon="calendar">Reservations</x-sidebar-menu-item>
                <x-sidebar-menu-item route="rooms.index" icon="bed">Rooms</x-sidebar-menu-item>
                <x-sidebar-menu-item route="guests.index" icon="users">Guests</x-sidebar-menu-item>
                <x-sidebar-menu-item route="services.index" icon="concierge-bell">Services</x-sidebar-menu-item>
                <x-sidebar-menu-item route="invoices.index" icon="file-text">Invoices</x-sidebar-menu-item>
                <x-sidebar-menu-item route="reports.index" icon="bar-chart">Reports</x-sidebar-menu-item>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="flex items-center justify-between h-16 px-6 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="relative w-full max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15.803 15.803M15.803 15.803C17.2096 14.3964 18 12.4836 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18C12.4836 18 14.3964 17.2096 15.803 15.803Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input type="text" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-400 dark:focus:border-indigo-300 focus:ring-indigo-300 focus:ring-opacity-40 focus:outline-none focus:ring" placeholder="Search">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <x-top-nav-user-menu />
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-800">
                <div class="container mx-auto px-6 py-8">
                    @if (isset($header))
                        <header class="mb-6">
                            {{ $header }}
                        </header>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
