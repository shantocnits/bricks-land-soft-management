<!DOCTYPE html>
<html lang="bn" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true', 
          sidebarOpen: false 
      }" 
      x-init="sidebarOpen = window.innerWidth >= 768; window.addEventListener('resize', () => { if (window.innerWidth < 768) sidebarOpen = false; }); $watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } })"
      :class="{ 'dark': darkMode }">
<head>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bricks Land Management Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🧱%3C/text%3E%3C/svg%3E">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-calendar { border-radius: 1rem !important; box-shadow: 0 20px 60px rgba(0,0,0,0.18) !important; border: 1px solid #e5e7eb !important; font-family: 'Inter', sans-serif !important; overflow: hidden; }
        .dark .flatpickr-calendar { background: #0f172a !important; border-color: #1e293b !important; color: #e2e8f0 !important; }
        .dark .flatpickr-months, .dark .flatpickr-weekdays { background: #0f172a !important; }
        .dark .flatpickr-day { color: #cbd5e1 !important; }
        .dark .flatpickr-day:hover { background: #1e293b !important; }
        .dark .flatpickr-day.selected { background: #059669 !important; border-color: #059669 !important; color: #fff !important; }
        .flatpickr-day.selected { background: #059669 !important; border-color: #059669 !important; }
        .dark .flatpickr-current-month, .dark .flatpickr-month { color: #e2e8f0 !important; }
        .dark .numInputWrapper input { color: #e2e8f0 !important; }
        .dark .flatpickr-prev-month svg, .dark .flatpickr-next-month svg { fill: #94a3b8 !important; }
        .dark .flatpickr-weekday { color: #64748b !important; }
        .flatpickr-input { cursor: pointer !important; }
    </style>
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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        var fpLocale = {
            firstDayOfWeek: 6,
            weekdays: {
                shorthand: ['রবি','সোম','মঙ্গল','বুধ','বৃহ','শুক্র','শনি'],
                longhand: ['রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার','শনিবার']
            },
            months: {
                shorthand: ['জান','ফেব','মার্চ','এপ্রি','মে','জুন','জুলাই','আগ','সেপ','অক্টো','নভে','ডিসে'],
                longhand: ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর']
            }
        };

        function initFlatpickrs() {
            document.querySelectorAll('[data-flatpickr]').forEach(function(el) {
                if (el._flatpickr) {
                    return;
                }
                var wireProp = el.getAttribute('data-wire-prop');
                var options = {
                    locale: fpLocale,
                    dateFormat: 'Y-m-d',
                    allowInput: false,
                    disableMobile: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (!wireProp || !dateStr) return;
                        var node = el;
                        while (node && node !== document.body) {
                            if (node.__livewire || (node.dataset && node.dataset.component)) break;
                            node = node.parentElement;
                        }
                        try {
                            var event = new CustomEvent('flatpickr-change', {
                                detail: { prop: wireProp, value: dateStr },
                                bubbles: true
                            });
                            el.dispatchEvent(event);
                            var closestWire = el.closest('[wire\\:id]');
                            if (closestWire) {
                                var wireId = closestWire.getAttribute('wire:id');
                                var comp = Livewire.find(wireId);
                                if (comp) { comp.set(wireProp, dateStr); return; }
                            }
                            Livewire.all().forEach(function(c) {
                                try { if (c.get(wireProp) !== undefined) c.set(wireProp, dateStr); } catch(e) {}
                            });
                        } catch(e) { console.warn('Flatpickr Livewire set error:', e); }
                    }
                };
                var defaultDate = el.getAttribute('data-default') || el.value;
                if (defaultDate) options.defaultDate = defaultDate;
                flatpickr(el, options);
            });
        }

        document.addEventListener('DOMContentLoaded', function() { setTimeout(initFlatpickrs, 200); });
        document.addEventListener('livewire:navigated', function() { setTimeout(initFlatpickrs, 200); });
        document.addEventListener('livewire:update', function() { setTimeout(initFlatpickrs, 100); });
        var fpObserver = new MutationObserver(function(mutations) {
            var hasFp = false;
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(n) {
                    if (n.nodeType === 1 && (n.querySelector && n.querySelector('[data-flatpickr]') || n.matches && n.matches('[data-flatpickr]'))) hasFp = true;
                });
            });
            if (hasFp) setTimeout(initFlatpickrs, 100);
        });
        fpObserver.observe(document.body, { childList: true, subtree: true });
    </script>
</body>
</html>
