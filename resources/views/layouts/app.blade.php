<!DOCTYPE html>
<html lang="bn">
<head>
    <script>
        try {
            var stored = localStorage.getItem('_x_darkMode') || localStorage.getItem('darkMode');
            if (stored === 'true' || stored === '"true"') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (_) {}
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
        #nprogress { display: none !important; }
    </style>
</head>
<body x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true' || localStorage.getItem('_x_darkMode') === 'true', 
          sidebarOpen: false 
      }"
      x-init="
          sidebarOpen = window.innerWidth >= 768; 
          window.addEventListener('resize', () => { if (window.innerWidth < 768) sidebarOpen = false; });
          if (darkMode) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }
          $watch('darkMode', val => { 
              localStorage.setItem('darkMode', val);
              localStorage.setItem('_x_darkMode', val);
              if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } 
          })
      "
      class="font-sans antialiased bg-gray-50 text-gray-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-300">
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
                    var expectedVal = el.getAttribute('data-default') || el.value;
                    if (expectedVal && el.value !== expectedVal) {
                        el._flatpickr.setDate(expectedVal, false);
                    }
                    return;
                }

                // Add wire:ignore to parent dynamically to prevent Livewire from deleting altInput
                if (el.parentElement) {
                    el.parentElement.setAttribute('wire:ignore', '');
                }

                // Sibling check: clean up stale flatpickr-input elements created as altInput
                var next = el.nextElementSibling;
                if (next && next.classList.contains('flatpickr-input') && !next.hasAttribute('data-flatpickr')) {
                    next.remove();
                }

                var wireProp = el.getAttribute('data-wire-prop');
                var options = {
                    locale: fpLocale,
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    altInputClass: el.className,
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

        var fpObserver;
        function setupObserver() {
            if (fpObserver) {
                try { fpObserver.disconnect(); } catch(e) {}
            }
            fpObserver = new MutationObserver(function(mutations) {
                var hasFp = false;
                mutations.forEach(function(m) {
                    m.addedNodes.forEach(function(n) {
                        if (n.nodeType === 1 && (n.querySelector && n.querySelector('[data-flatpickr]') || n.matches && n.matches('[data-flatpickr]'))) hasFp = true;
                    });
                });
                if (hasFp) setTimeout(initFlatpickrs, 100);
            });
            fpObserver.observe(document.body, { childList: true, subtree: true });
        }

        document.addEventListener('DOMContentLoaded', function() { 
            setupObserver();
            setTimeout(initFlatpickrs, 200); 
        });
        document.addEventListener('livewire:navigated', function() { 
            setupObserver();
            setTimeout(initFlatpickrs, 200); 
        });
        document.addEventListener('livewire:update', function() { setTimeout(initFlatpickrs, 100); });

        // ===== Disable Livewire Navigation Progress Bar =====
        document.addEventListener("livewire:init", () => {
            if (window.Alpine && window.Alpine.navigate) {
                window.Alpine.navigate.disableProgressBar();
            }
        });

        // ===== Print Challan Area =====
        window.printChallanArea = function(printAreaId) {
            var el = document.getElementById(printAreaId);
            if (!el) { window.print(); return; }
            var clone = el.cloneNode(true);
            clone.id = '__print_clone__';
            clone.style.cssText = '';
            clone.removeAttribute('class');
            var style = document.createElement('style');
            style.textContent = [
                '@media print {',
                '  body * { visibility: hidden !important; }',
                '  #__print_clone__, #__print_clone__ * { visibility: visible !important; }',
                '  #__print_clone__ { position:fixed!important;left:0!important;top:0!important;width:100%!important;background:#fff!important;padding:12mm 10mm!important;margin:0!important;z-index:999999!important; }',
                '  @page { size: A4 portrait; margin:0; }',
                '}'
            ].join('\n');
            document.head.appendChild(style);
            document.body.appendChild(clone);
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    document.body.removeChild(clone);
                    document.head.removeChild(style);
                }, 500);
            }, 100);
        };
    </script>

    <!-- Universal Floating Action Buttons & Professional Modals Widget Area -->
    <div x-data="{
        callOpen: false,
        weatherOpen: false,
        weatherLoading: false,
        cityDropdownOpen: false,
        selectedCity: 'Dhaka',
        weatherData: null,
        cities: {
            'Dhaka': { lat: 23.8103, lon: 90.4125, name: 'ঢাকা' },
            'Chittagong': { lat: 22.3569, lon: 91.7832, name: 'চট্টগ্রাম' },
            'Sylhet': { lat: 24.8949, lon: 91.8687, name: 'সিলেট' },
            'Rajshahi': { lat: 24.3745, lon: 88.6042, name: 'রাজশাহী' },
            'Khulna': { lat: 22.8456, lon: 89.5403, name: 'খুলনা' },
            'Barisal': { lat: 22.7010, lon: 90.3535, name: 'বরিশাল' },
            'Rangpur': { lat: 25.7558, lon: 89.2444, name: 'রংপুর' },
            'Mymensingh': { lat: 24.7471, lon: 90.4203, name: 'ময়মনসিংহ' }
        },
        getWeatherDesc(code) {
            // Weather conditions categorized in Bengali
            if (code === 0) return { text: 'পরিষ্কার রৌদ্রোজ্জ্বল আকাশ', icon: '☀️', bg: 'from-amber-400 to-orange-500' };
            if ([1, 2, 3].includes(code)) return { text: 'আংশিক মেঘলা', icon: '⛅', bg: 'from-sky-400 to-blue-500' };
            if ([45, 48].includes(code)) return { text: 'ঘন কুয়াশাচ্ছন্ন', icon: '🌫️', bg: 'from-slate-400 to-gray-500' };
            if ([51, 53, 55, 61, 63, 65, 80, 81, 82].includes(code)) return { text: 'হালকা থেকে মাঝারি বৃষ্টিপাত', icon: '🌧️', bg: 'from-indigo-500 to-blue-700' };
            if ([95, 96, 99].includes(code)) return { text: 'বজ্রঝড় সহ বৃষ্টি', icon: '⛈️', bg: 'from-purple-800 to-indigo-950' };
            return { text: 'মেঘলা আকাশ', icon: '☁️', bg: 'from-slate-500 to-slate-700' };
        },
        getWindDir(degrees) {
            if (degrees >= 337.5 || degrees < 22.5) return 'উত্তর';
            if (degrees >= 22.5 && degrees < 67.5) return 'উত্তর-পূর্ব';
            if (degrees >= 67.5 && degrees < 112.5) return 'পূর্ব';
            if (degrees >= 112.5 && degrees < 157.5) return 'দক্ষিণ-পূর্ব';
            if (degrees >= 157.5 && degrees < 202.5) return 'দক্ষিণ';
            if (degrees >= 202.5 && degrees < 247.5) return 'দক্ষিণ-পশ্চিম';
            if (degrees >= 247.5 && degrees < 292.5) return 'পশ্চিম';
            return 'উত্তর-পশ্চিম';
        },
        async fetchWeather() {
            this.weatherLoading = true;
            const city = this.cities[this.selectedCity];
            try {
                // Fetch current + daily data for UV and precipitation probability
                const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${city.lat}&longitude=${city.lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,weather_code,wind_speed_10m,wind_direction_10m&daily=temperature_2m_max,temperature_2m_min,uv_index_max,precipitation_probability_max&timezone=Asia/Dhaka`);
                const data = await res.json();
                this.weatherData = {
                    temp: data.current.temperature_2m,
                    feel: data.current.apparent_temperature,
                    humidity: data.current.relative_humidity_2m,
                    code: data.current.weather_code,
                    windSpeed: data.current.wind_speed_10m,
                    windDir: this.getWindDir(data.current.wind_direction_10m),
                    uv: data.daily.uv_index_max[0],
                    rainProb: data.daily.precipitation_probability_max[0],
                    tempMax: data.daily.temperature_2m_max[0],
                    tempMin: data.daily.temperature_2m_min[0],
                    lat: city.lat,
                    lon: city.lon
                };
            } catch (err) {
                console.error(err);
            } finally {
                this.weatherLoading = false;
            }
        }
    }" x-init="$watch('selectedCity', () => fetchWeather())">

        <!-- Universal Floating Action Buttons (Bottom Right, Back-to-Top Style) -->
        <div class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3.5">
            <!-- 1. Weather FAB -->
            <button 
                @click="weatherOpen = true; fetchWeather();" 
                class="w-12.5 h-12.5 bg-gradient-to-tr from-sky-400 to-blue-600 hover:from-sky-500 hover:to-blue-700 text-white rounded-full flex items-center justify-center shadow-2xl hover:shadow-sky-500/30 active:scale-95 hover:scale-105 transition-all duration-200 cursor-pointer focus:outline-none ring-4 ring-sky-400/20"
                title="আবহাওয়া আপডেট">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                </svg>
            </button>

            <!-- 2. Support / Call FAB -->
            <button 
                @click="callOpen = true" 
                class="w-12.5 h-12.5 bg-gradient-to-tr from-emerald-400 to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white rounded-full flex items-center justify-center shadow-2xl hover:shadow-emerald-500/30 active:scale-95 hover:scale-105 transition-all duration-200 cursor-pointer focus:outline-none ring-4 ring-emerald-400/20"
                title="যোগাযোগ ও সাপোর্ট">
                <!-- Call / Phone Icon -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a12.035 12.035 0 01-7.108-7.108c-.145-.44.02-.902.387-.769L9.74 9.76a1.244 1.244 0 00-.417-1.173L5.207 7.48a1.125 1.125 0 00-1.091.852H2.25v2.25z" />
                </svg>
            </button>
        </div>

        <!-- Call Contact Modal (Professional Redesign) -->
        <div 
            x-show="callOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="callOpen = false"
            @keydown.escape.window="callOpen = false"
            class="fixed inset-0 z-[99999] bg-black/60 backdrop-blur-md flex items-center justify-center p-4"
            x-cloak>
            
            <div 
                x-show="callOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 max-w-md w-full relative overflow-hidden">
                
                <!-- Decorative colored gradient background overlay -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>

                <!-- Close Button -->
                <button @click="callOpen = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Content -->
                <div class="text-left space-y-6 pt-2">
                    <h2 class="text-xl md:text-2xl font-extrabold text-gray-800 dark:text-white font-sans tracking-wide">
                        প্রয়োজনে যোগাযোগ করুন
                    </h2>

                    <!-- Contact details -->
                    <div class="space-y-4 text-left max-w-full pt-4">
                        <!-- Phone Link -->
                        <a href="tel:+8801918908070" class="flex items-center gap-4 p-2.5 rounded-2xl hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition-all group">
                            <span class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a12.035 12.035 0 01-7.108-7.108c-.145-.44.02-.902.387-.769L9.74 9.76a1.244 1.244 0 00-.417-1.173L5.207 7.48a1.125 1.125 0 00-1.091.852H2.25v2.25z"/>
                                </svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 font-sans uppercase">ফোন করুন</span>
                                <span class="text-sm font-black text-gray-800 dark:text-slate-200 font-mono group-hover:text-emerald-600 transition-colors">+8801918-908070</span>
                            </div>
                        </a>

                        <!-- Web Link -->
                        <a href="https://www.payratech.com" target="_blank" class="flex items-center gap-4 p-2.5 rounded-2xl hover:bg-blue-50/50 dark:hover:bg-blue-950/20 transition-all group">
                            <span class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.003 9.003 0 008.354-5.646m-8 5.646a9.003 9.003 0 01-8.354-5.646m8 5.646V12m0 0V3m0 9h8.354M12 12H3.646m16.708 0a9 9 0 10-18 0c0 .916.137 1.8.39 2.632"/>
                                </svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 font-sans uppercase">ওয়েবসাইট</span>
                                <span class="text-sm font-black text-gray-800 dark:text-slate-200 font-mono group-hover:text-blue-600 transition-colors">www.payratech.com</span>
                            </div>
                        </a>

                        <!-- Email Link -->
                        <a href="mailto:support@payratech.com" class="flex items-center gap-4 p-2.5 rounded-2xl hover:bg-orange-50/50 dark:hover:bg-orange-950/20 transition-all group">
                            <span class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 font-sans uppercase">ইমেইল</span>
                                <span class="text-sm font-black text-gray-800 dark:text-slate-200 font-mono group-hover:text-orange-600 transition-colors">support@payratech.com</span>
                            </div>
                        </a>
                    </div>

                    <!-- Footer Note -->
                    <div class="pt-4 text-xs font-black text-orange-500 font-sans tracking-wide">
                        (সকাল ৯ টা হতে রাত ৮ টা)
                    </div>
                </div>

            </div>
        </div>

        <!-- Weather Modal (Full, Large max-w-4xl Redesign) -->
        <div 
            x-show="weatherOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="weatherOpen = false"
            @keydown.escape.window="weatherOpen = false"
            class="fixed inset-0 z-[99999] bg-black/60 backdrop-blur-md flex items-center justify-center p-4 md:p-6"
            x-cloak>
            
            <div 
                x-show="weatherOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 p-6 md:p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
                
                <!-- Close Button -->
                <button @click="weatherOpen = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-250 cursor-pointer focus:outline-none z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Content Grid Layout -->
                <div class="space-y-6">
                    <!-- Title & Custom Dropdown in one line matching Root select -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 dark:border-slate-800 pb-4 pt-6">
                        <h2 class="text-lg md:text-xl font-extrabold text-gray-800 dark:text-white font-sans tracking-wide">
                            বাংলাদেশ আবহাওয়া ড্যাশবোর্ড
                        </h2>
                        
                        <!-- Root Styled Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" 
                                    class="flex items-center justify-between gap-2.5 px-4 py-2 bg-emerald-50 dark:bg-emerald-950/20 text-[#034C3C] dark:text-emerald-300 font-bold rounded-full text-xs border border-emerald-200 dark:border-emerald-900/60 focus:outline-none transition-all duration-150 cursor-pointer min-w-[130px]">
                                <span x-text="'শহরঃ ' + cities[selectedCity].name" class="font-sans"></span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200 text-emerald-700 dark:text-emerald-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-1.5 w-40 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-50 text-xs overflow-hidden"
                                 x-cloak>
                                <template x-for="(cityInfo, key) in cities" :key="key">
                                    <button type="button" 
                                            @click="selectedCity = key; open = false;" 
                                            class="w-full text-left px-3.5 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-bold transition-all font-sans"
                                            x-text="cityInfo.name">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Inner Loading / Content Wrapper -->
                    <div class="relative min-h-[300px] flex flex-col justify-center items-center">
                        <!-- Loading Spinner -->
                        <div x-show="weatherLoading" class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 flex items-center justify-center z-20">
                            <div class="w-10 h-10 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>

                        <template x-if="weatherData">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                                <!-- LEFT: Detailed Weather Cards -->
                                <div class="space-y-4">
                                    <!-- Main Temperature Card -->
                                    <div class="p-5 rounded-3xl text-white bg-gradient-to-tr shadow-lg flex items-center justify-between" :class="getWeatherDesc(weatherData.code).bg">
                                        <div class="space-y-1">
                                            <span class="text-xs font-black tracking-widest uppercase opacity-90 font-sans block" x-text="cities[selectedCity].name + ' আবহাওয়া'"></span >
                                            <span class="text-4xl md:text-5xl font-black font-sans leading-none block" x-text="Math.round(weatherData.temp) + '°C'"></span>
                                            <span class="text-xs font-bold font-sans opacity-95 block" x-text="getWeatherDesc(weatherData.code).text"></span>
                                        </div>
                                        <span class="text-6xl filter drop-shadow-md" x-text="getWeatherDesc(weatherData.code).icon"></span>
                                    </div>

                                    <!-- Grid of Information metrics -->
                                    <div class="grid grid-cols-2 gap-3.5">
                                        <!-- RealFeel -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">অনুভূত তাপমাত্রা</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block" x-text="Math.round(weatherData.feel) + '°C'"></span>
                                        </div>
                                        <!-- Min/Max Temp -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">সর্বোচ্চ / সর্বনিম্ন</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block" x-text="Math.round(weatherData.tempMax) + '° / ' + Math.round(weatherData.tempMin) + '°'"></span>
                                        </div>
                                        <!-- Humidity -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">আর্দ্রতা</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block" x-text="weatherData.humidity + '%'"></span>
                                        </div>
                                        <!-- Rain Probability -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">বৃষ্টির সম্ভাবনা</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block" x-text="weatherData.rainProb + '%'"></span>
                                        </div>
                                        <!-- Wind Speed & Direction -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">বাতাস</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block leading-tight" x-text="weatherData.windSpeed + ' কিমি/ঘণ্টা (' + weatherData.windDir + ')'"></span>
                                        </div>
                                        <!-- UV Index -->
                                        <div class="bg-gray-50 dark:bg-slate-950 p-3.5 rounded-2xl border border-gray-150 dark:border-slate-800">
                                            <span class="text-[10px] font-bold text-gray-400 block font-sans uppercase">ইউভি ইনডেক্স</span>
                                            <span class="text-sm font-black text-gray-700 dark:text-slate-200 font-sans mt-0.5 block" x-text="weatherData.uv + ' (সর্বোচ্চ)'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT: Location Map Frame (Google Maps Dynamic Embed) -->
                                <div class="bg-gray-50 dark:bg-slate-950 rounded-3xl border border-gray-150 dark:border-slate-800/80 p-3 flex flex-col h-full min-h-[250px] md:min-h-full">
                                    <span class="text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-2 font-sans block uppercase pl-1">ভৌগোলিক মানচিত্র</span>
                                    <div class="flex-grow rounded-2xl overflow-hidden border border-gray-200 dark:border-slate-800">
                                        <iframe 
                                            x-bind:src="`https://maps.google.com/maps?q=${weatherData.lat},${weatherData.lon}&z=11&output=embed`" 
                                            class="w-full h-full min-h-[240px] md:min-h-[300px]" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
