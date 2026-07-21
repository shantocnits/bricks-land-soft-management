@php
    $title = 'ড্যাশবোর্ড';
    if (request()->routeIs('challan.today')) {
        $title = 'দৈনিক চালান তালিকা';
    } elseif (request()->routeIs('challan.pending')) {
        $title = 'অগ্রিম চালান তালিকা';
    } elseif (request()->routeIs('challan.all')) {
        $title = 'সকল চালান তালিকা';
    } elseif (request()->routeIs('challan.customer-profile')) {
        $title = 'গ্রাহক প্রোফাইল';
    } elseif (request()->routeIs('settings')) {
        $title = 'সেটিংস';
    } elseif (request()->routeIs('user-management')) {
        $title = 'ইউজার ম্যানেজমেন্ট';
    } elseif (request()->routeIs('update-history')) {
        $title = 'আপডেট হিস্ট্রি';
    } elseif (request()->routeIs('login-history')) {
        $title = 'লগইন রেকর্ড';
    } elseif (request()->routeIs('tutorial')) {
        $title = 'ভিডিও টিউটোরিয়াল';
    } elseif (request()->routeIs('fee-payment')) {
        $title = 'ফি পেমেন্ট';
    } elseif (request()->routeIs('payment-khata')) {
        $title = 'পেমেন্ট খাতা';
    } elseif (request()->routeIs('cash-khata')) {
        $title = 'ক্যাশ খাতা';
    } elseif (request()->routeIs('load-khata')) {
        $title = 'লোড খাতা';
    } elseif (request()->routeIs('unload-khata')) {
        $title = 'আনলোড';
    } elseif (request()->routeIs('stock-khata')) {
        $title = 'স্টক খাতা';
    } elseif (request()->routeIs('khotian')) {
        $title = 'খতিয়ান';
    } elseif (request()->routeIs('customer')) {
        $title = 'কাস্টমার';
    } elseif (request()->routeIs('delivery.today')) {
        $title = 'আজকের ডেলিভারি';
    } elseif (request()->routeIs('delivery.pending')) {
        $title = 'বাকি ডেলিভারি';
    } elseif (request()->routeIs('delivery.all')) {
        $title = 'সকল ডেলিভারি';
    } elseif (request()->routeIs('about-us')) {
        $title = 'আমাদের সম্পর্কে';
    } elseif (request()->routeIs('faq')) {
        $title = 'সাধারণ জিজ্ঞাসা';
    } elseif (request()->routeIs('due-ledger.today')) {
        $title = 'আজকের জমা';
    } elseif (request()->routeIs('due-ledger.due-today')) {
        $title = 'আজ জমা দেবে';
    } elseif (request()->routeIs('due-ledger.all-due')) {
        $title = 'সব বাকি লিস্ট';
    }
    $currentUser = auth()->user();
@endphp
<header class="sticky top-0 z-30 flex items-center justify-between px-6 h-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-gray-200 dark:border-slate-800 transition-colors duration-300 shadow-sm flex-shrink-0">
    
    <!-- Left Section: Sidebar Toggle & Title -->
    <div class="flex items-center space-x-3">
        <!-- Sidebar Toggle -->
        <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none rounded-xl p-2 transition-colors duration-150 border border-gray-200/20 shadow-sm bg-white dark:bg-slate-900"
            aria-label="Toggle Sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <!-- Brand Logo Icon -->
        <span class="text-xl select-none leading-none md:hidden">🧱</span>
        
        <!-- Breadcrumb / Title -->
        <span class="text-lg font-bold text-gray-800 dark:text-white font-sans hidden sm:inline">{{ $title }}</span>
    </div>

    <!-- Right Section: Actions & Profile -->
    <div class="flex items-center space-x-2 md:space-x-4">
        
        <!-- Financial Year Dropdown -->
        <div x-data="{ open: false, selected: 'হিসাবঃ ২৩-২৪' }" class="relative">
            <button @click="open = !open" 
                    class="flex items-center justify-between space-x-2 px-4 py-1.5 bg-primary-50 dark:bg-primary-950/20 text-primary-900 dark:text-primary-300 font-semibold rounded-full text-xs border border-primary-200 dark:border-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-505/20 transition-all duration-150 cursor-pointer">
                <span x-text="selected" class="font-sans"></span>
                <svg class="w-3.5 h-3.5 transition-transform duration-200 text-primary-dark dark:text-primary-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute left-0 mt-1.5 w-36 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-40 text-xs overflow-hidden"
                 x-cloak>
                <button @click="selected = 'হিসাবঃ ২৩-২৪'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-950/10 hover:text-primary-dark dark:hover:text-primary-400 font-semibold transition-all font-sans">হিসাবঃ ২৩-২৪</button>
                <button @click="selected = 'হিসাবঃ ২৪-২৫'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-950/10 hover:text-primary-dark dark:hover:text-primary-400 font-semibold transition-all font-sans">হিসাবঃ ২৪-২৫</button>
            </div>
        </div>

        <!-- Utility Buttons Group -->
        <div class="hidden sm:flex items-center space-x-2">
            <!-- 1. Update Record -->
            <div x-data="{ hover: false }" class="relative flex items-center justify-center">
                <a href="{{ route('update-history') }}" wire:navigate @mouseenter="hover = true" @mouseleave="hover = false" 
                   class="p-1.5 rounded-full transition-all focus:outline-none cursor-pointer {{ request()->routeIs('update-history') ? 'bg-[#034C3C]/10 text-[#034C3C] dark:bg-primary-500/10 dark:text-primary-400 ring-2 ring-primary-500/20 shadow-inner' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </a>
                <div 
                    x-show="hover"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                    x-cloak>
                    আপডেট রেকর্ড
                </div>
            </div>
            
            <!-- 2. Login Record -->
            <div x-data="{ hover: false }" class="relative flex items-center justify-center">
                <a href="{{ route('login-history') }}" wire:navigate @mouseenter="hover = true" @mouseleave="hover = false" 
                   class="p-1.5 rounded-full transition-all focus:outline-none cursor-pointer {{ request()->routeIs('login-history') ? 'bg-[#034C3C]/10 text-[#034C3C] dark:bg-primary-500/10 dark:text-primary-400 ring-2 ring-primary-500/20 shadow-inner' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </a>
                <div 
                    x-show="hover"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                    x-cloak>
                    লগইন রেকর্ড
                </div>
            </div>
            
            <!-- 3. Video Tutorial -->
            <div x-data="{ hover: false }" class="relative flex items-center justify-center">
                <a href="{{ route('tutorial') }}" wire:navigate @mouseenter="hover = true" @mouseleave="hover = false"
                   class="p-1.5 rounded-full transition-all focus:outline-none cursor-pointer {{ request()->routeIs('tutorial') ? 'bg-[#034C3C]/10 text-[#034C3C] dark:bg-primary-500/10 dark:text-primary-400 ring-2 ring-primary-500/20 shadow-inner' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>
                <div 
                    x-show="hover"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                    x-cloak>
                    ভিডিও টিউটোরিয়াল
                </div>
            </div>
            
            <!-- 4. Payment Method -->
            <div x-data="{ hover: false }" class="relative flex items-center justify-center">
                <a href="{{ route('fee-payment') }}" wire:navigate @mouseenter="hover = true" @mouseleave="hover = false"
                   class="p-1.5 rounded-full transition-all focus:outline-none cursor-pointer {{ request()->routeIs('fee-payment') ? 'bg-[#034C3C]/10 text-[#034C3C] dark:bg-primary-500/10 dark:text-primary-400 ring-2 ring-primary-500/20 shadow-inner' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                </a>
                <div 
                    x-show="hover"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                    x-cloak>
                    ফি পেমেন্ট
                </div>
            </div>
        </div>

        <!-- Divider -->
        <span class="h-6 w-px bg-gray-200 dark:bg-slate-700 hidden sm:inline"></span>

        <!-- Theme Toggle Button -->
        <div x-data="{ hover: false }" class="relative flex items-center justify-center">
            <button 
                @click="darkMode = !darkMode" 
                @mouseenter="hover = true" @mouseleave="hover = false"
                class="p-1.5 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors focus:outline-none cursor-pointer">
                <!-- Sun Icon (shown in dark mode) -->
                <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.364 17.636l-.707.707M16.243 16.243l.707.707M7.757 7.757l.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z"/>
                </svg>
                <!-- Moon Icon (shown in light mode) -->
                <svg x-show="!darkMode" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            <div 
                x-show="hover"
                x-transition:enter="transition ease-out duration-200 transform"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                x-cloak>
                থিম পরিবর্তন
            </div>
        </div>

        <!-- Profile / Account -->
        <div x-data="{ open: false, hover: false }" class="relative flex items-center justify-center">
            <button @click="open = !open" @mouseenter="hover = true" @mouseleave="hover = false" class="flex items-center focus:outline-none cursor-pointer">
                @if($currentUser && $currentUser->profile_photo)
                    <img src="{{ asset('storage/' . $currentUser->profile_photo) }}" class="h-8 w-8 rounded-full object-cover shadow-sm ring-2 ring-primary-100 dark:ring-primary-950">
                @else
                    <div class="h-8 w-8 rounded-full overflow-hidden flex items-center justify-center relative shadow-inner ring-2 ring-primary-100 dark:ring-primary-950 bg-gray-50 dark:bg-slate-950">
                        <div class="absolute inset-y-0 left-0 right-1/2 bg-[#F59E0B]"></div>
                        <div class="absolute inset-y-0 right-0 left-1/2 bg-[#009E74]"></div>
                        <svg class="w-5 h-5 text-white relative z-10 filter drop-shadow-sm" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.25,12.75 C20,12 18,12 16.5,12.5 C15.5,12.83 14,14 13.5,15.5 C13,17 13.5,19 14.5,20 C14,20 12,18 11.5,16.5 C11,15 11.25,13.25 12,11.5 C12.25,11 11,11 10.5,11.5 C9.5,12.5 8,14.5 7,16.5 C6,18.5 5.5,20 5.5,20 C5.5,20 5.8,18 6,16 C6.2,14 6,11.5 5.5,10 C5.2,9.1 4.5,8.2 4.1,8 C3.8,7.9 3.5,8.1 3.5,8.5 C3.5,9.5 4,11.5 4,13.5 C4,15.5 3.5,17 3.5,17 C3.5,17 3.25,15.5 3,14 C2.75,12.5 2.25,11.5 2.25,11 C2.25,10.5 2.5,10 3,9.5 C4.5,8 7.5,6.5 10.5,6.5 C11.5,6.5 12.5,6.75 13.5,7 C14,7.1 14.5,6.9 14.5,6.5 C14.5,6.1 14,5.9 13.5,5.8 C11.5,5.4 9,6.2 7,7.2 C8.5,5.8 10.5,5 12.5,5 C15.5,5 18,6.5 19.5,8.5 C20,9.2 20.5,10 20.8,10.8 C21,11.3 21.25,11.8 21.25,12.25 C21.25,12.5 21,12.6 21.25,12.75 Z"/>
                        </svg>
                    </div>
                @endif
            </button>
            
            <div 
                x-show="hover && !open"
                x-transition:enter="transition ease-out duration-200 transform"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-primary text-white text-[10px] font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark font-sans"
                x-cloak>
                আমার প্রোফাইল
            </div>
            
            <!-- Dropdown Menu -->
            <div 
                x-show="open" 
                @click.away="open = false" 
                x-transition
                class="absolute right-0 mt-2 top-full w-64 bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-800 py-0 z-30 text-xs overflow-hidden"
                x-cloak>
                
                <!-- Profile Header Card -->
                <div class="bg-gray-50 dark:bg-slate-800/40 p-5 flex flex-col items-center border-b border-gray-100 dark:border-slate-800">
                    <div class="h-16 w-16 rounded-full overflow-hidden flex items-center justify-center relative shadow-inner mb-3 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950">
                        @if($currentUser && $currentUser->profile_photo)
                            <img src="{{ asset('storage/' . $currentUser->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-y-0 left-0 right-1/2 bg-[#F59E0B]"></div>
                            <div class="absolute inset-y-0 right-0 left-1/2 bg-[#009E74]"></div>
                            <svg class="w-10 h-10 text-white relative z-10 filter drop-shadow-sm" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.25,12.75 C20,12 18,12 16.5,12.5 C15.5,12.83 14,14 13.5,15.5 C13,17 13.5,19 14.5,20 C14,20 12,18 11.5,16.5 C11,15 11.25,13.25 12,11.5 C12.25,11 11,11 10.5,11.5 C9.5,12.5 8,14.5 7,16.5 C6,18.5 5.5,20 5.5,20 C5.5,20 5.8,18 6,16 C6.2,14 6,11.5 5.5,10 C5.2,9.1 4.5,8.2 4.1,8 C3.8,7.9 3.5,8.1 3.5,8.5 C3.5,9.5 4,11.5 4,13.5 C4,15.5 3.5,17 3.5,17 C3.5,17 3.25,15.5 3,14 C2.75,12.5 2.25,11.5 2.25,11 C2.25,10.5 2.5,10 3,9.5 C4.5,8 7.5,6.5 10.5,6.5 C11.5,6.5 12.5,6.75 13.5,7 C14,7.1 14.5,6.9 14.5,6.5 C14.5,6.1 14,5.9 13.5,5.8 C11.5,5.4 9,6.2 7,7.2 C8.5,5.8 10.5,5 12.5,5 C15.5,5 18,6.5 19.5,8.5 C20,9.2 20.5,10 20.8,10.8 C21,11.3 21.25,11.8 21.25,12.25 C21.25,12.5 21,12.6 21.25,12.75 Z"/>
                            </svg>
                        @endif
                    </div>
                    <span class="font-bold text-sm text-gray-800 dark:text-white font-sans">{{ $currentUser->name ?? 'Demo' }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-sans">{{ $currentUser->email ?? '' }}</span>
                    <span class="text-xs font-semibold text-[#E57E22] mt-1.5 font-sans">
                        {{ $currentUser->role === 'admin' ? 'এডমিন (Admin)' : ($currentUser->role === 'demo' ? 'ডেমো (Demo)' : 'ইউজার (User)') }}
                    </span>
                </div>

                <!-- Dropdown items -->
                <div class="py-1">
                    <a href="{{ route('settings', ['tab' => 'my_profile']) }}" wire:navigate class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-950/10 hover:text-[#034C3C] dark:hover:text-primary-400 font-semibold transition-all">
                        <svg class="w-4 h-4 mr-2.5 text-primary flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span class="font-sans">আমার প্রোফাইল</span>
                    </a>

                    <a href="{{ route('faq') }}" wire:navigate class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-950/10 hover:text-[#034C3C] dark:hover:text-primary-400 font-semibold transition-all">
                        <span class="w-5 text-center font-extrabold text-sm mr-2 select-none">?</span>
                        <span class="font-sans">সাধারণ জিজ্ঞাসা</span>
                    </a>
                    
                    <a href="{{ route('about-us') }}" wire:navigate class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-950/10 hover:text-[#034C3C] dark:hover:text-primary-400 font-semibold transition-all">
                        <span class="w-5 text-center mr-2 flex items-center justify-center select-none">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="font-sans">আমাদের সম্পর্কে জানুন</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-[#E57E22] hover:bg-red-50 dark:hover:bg-red-950/10 font-bold transition-all cursor-pointer">
                            <span class="w-5 text-center mr-2 flex items-center justify-center select-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <span class="font-sans">লগআউট</span>
                        </button>
                    </form>
                </div>

                <!-- Footer band with App Version -->
                <div class="bg-gray-50 dark:bg-slate-800/40 py-2 border-t border-gray-100 dark:border-slate-800 text-center text-[10px] text-gray-400 dark:text-slate-500 font-bold tracking-wide select-none font-sans">
                    Version 3.6.4
                </div>
            </div>
        </div>

    </div>
</header>
