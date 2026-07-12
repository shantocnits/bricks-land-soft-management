<!DOCTYPE html>
<html lang="bn" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true', 
          sidebarOpen: false 
      }" 
      x-init="sidebarOpen = window.innerWidth >= 768; window.addEventListener('resize', () => { if (window.innerWidth < 768) sidebarOpen = false; }); $watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bricks Land Management Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🧱%3C/text%3E%3C/svg%3E">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden relative">
        
        <!-- Mobile Blur Overlay Backdrop -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:opacity
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 md:hidden"
            x-cloak>
        </div>

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden min-h-screen min-w-0 bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
            
            <!-- Topbar -->
            @include('layouts.partials.topbar')

            <!-- Main Content -->
            <main class="flex-grow p-4 md:p-6 lg:p-8 bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
            
        </div>
    </div>

    @livewireScripts
</body>
</html>
