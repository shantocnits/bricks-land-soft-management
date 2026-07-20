@php
    $currentUser = auth()->user();
    $hasAccess = function($menuKey) use ($currentUser) {
        if (!$currentUser) return false;
        try {
            if ($currentUser->hasRole('admin')) return true;
            return $currentUser->hasPermissionTo($menuKey);
        } catch (\Exception $e) {
            return false;
        }
    };
@endphp

<aside 
    x-data="{ 
        activeTooltip: '', 
        tooltipTop: 0,
        showTooltip(text, el) {
            if (!sidebarOpen) {
                this.activeTooltip = text;
                this.tooltipTop = el.getBoundingClientRect().top - el.closest('aside').getBoundingClientRect().top;
            }
        },
        hideTooltip() {
            this.activeTooltip = '';
        }
    }"
    class="fixed md:relative inset-y-0 left-0 flex flex-col bg-primary-sidebar text-white flex-shrink-0 transition-all duration-300 ease-in-out z-40 overflow-visible w-64"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full md:translate-x-0 md:w-20': !sidebarOpen }">
    
    <!-- Logo Area -->
    <div class="flex items-center px-4 h-16 bg-primary-sidebar-dark border-b border-primary-800 flex-shrink-0 gap-[10px]">
        <!-- Brick Logo Icon (Same as navbar) -->
        <div class="flex-shrink-0 p-2 bg-white rounded-lg shadow-md flex items-center justify-center h-10 w-10">
            <span class="text-xl select-none leading-none">🧱</span>
        </div>
        <div class="transition-all duration-300 overflow-hidden whitespace-nowrap flex-grow flex items-center" :class="{ 'opacity-100 max-w-full block': sidebarOpen, 'opacity-0 max-w-0 hidden': !sidebarOpen }">
            <div>
                <h1 class="font-bold text-lg tracking-wider capitalize">bricks <span class="text-primary-400">land</span></h1>
                <p class="text-[10px] text-primary-300 font-light truncate">ডেমো ব্রিকস, কলোনী বাজার, লালমনিরহাট</p>
            </div>
        </div>

        <!-- Close Button for Mobile Offcanvas -->
        <button 
            @click="sidebarOpen = false" 
            class="md:hidden text-white bg-primary-sidebar hover:bg-primary-sidebar-light p-2 rounded-xl focus:outline-none transition-colors duration-150 shadow-sm border border-primary-800 flex items-center justify-center flex-shrink-0"
            aria-label="Close Sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Scrollable Navigation Links -->
    <div class="flex-grow overflow-y-auto overflow-x-visible py-3 space-y-1 scrollbar-thin scrollbar-thumb-primary-800 scrollbar-track-transparent">
        
        <!-- 1. ড্যাশবোর্ড -->
        @if(auth()->check())
        <div class="mx-2">
            <a href="{{ route('dashboard') }}" wire:navigate
               @mouseenter="showTooltip('ড্যাশবোর্ড', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-secondary text-white shadow-sm' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ড্যাশবোর্ড</span>
            </a>
        </div>
        @endif

        <!-- 2. চালান (Dropdown) -->
        @if($hasAccess('challan'))
        <div class="mx-2" x-data="{ open: {{ request()->routeIs('challan.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    @mouseenter="showTooltip('চালান', $el)"
                    @mouseleave="hideTooltip()"
                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg {{ request()->routeIs('challan.*') ? 'bg-primary-800/50 text-white' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                          :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">চালান</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open, 'opacity-0 hidden': !sidebarOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 -translate-y-2" x-transition:enter-end="transform opacity-100 translate-y-0" class="mt-1 ml-6 space-y-0.5" :class="{ 'hidden': !sidebarOpen }">
                <a href="{{ route('challan.today') }}" wire:navigate
                   class="flex items-center gap-2 px-4 py-2 text-xs rounded-lg transition-all font-sans {{ request()->routeIs('challan.today') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    আজকের চালান
                </a>
                <a href="{{ route('challan.pending') }}" wire:navigate
                   class="flex items-center gap-2 px-4 py-2 text-xs rounded-lg transition-all font-sans {{ request()->routeIs('challan.pending') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    অগ্রিম চালান
                </a>
                <a href="{{ route('challan.all') }}" wire:navigate
                   class="flex items-center gap-2 px-4 py-2 text-xs rounded-lg transition-all font-sans {{ request()->routeIs('challan.all') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    সব চালান
                </a>
            </div>
        </div>
        @endif

        <!-- 3. পেমেন্ট খাতা -->
        @if($hasAccess('payment'))
        <div class="mx-2">
            <a href="{{ route('payment-khata') }}" wire:navigate
               @mouseenter="showTooltip('পেমেন্ট খাতা', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('payment-khata') ? 'bg-secondary text-white shadow-sm' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">পেমেন্ট খাতা</span>
            </a>
        </div>
        @endif

        <!-- 4. ডেলিভারি (Dropdown) -->
        @if($hasAccess('delivery'))
        <div class="mx-2" x-data="{ open: {{ request()->routeIs('delivery.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    @mouseenter="showTooltip('ডেলিভারি', $el)"
                    @mouseleave="hideTooltip()"
                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg {{ request()->routeIs('delivery.*') ? 'bg-primary-800/50 text-white' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                          :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ডেলিভারি</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open, 'opacity-0 hidden': !sidebarOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="mt-1 ml-6 space-y-1" :class="{ 'hidden': !sidebarOpen }">
                <a href="{{ route('delivery.today') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('delivery.today') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">আজকের ডেলিভারি</a>
                <a href="{{ route('delivery.pending') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('delivery.pending') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">আজ ডেলিভারি যাবে</a>
                <a href="{{ route('delivery.all') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('delivery.all') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">সব ডেলিভারি লিস্ট</a>
            </div>
        </div>
        @endif

        <!-- 5. বাকি খাতা (Dropdown) -->
        @if($hasAccess('due_ledger'))
        <div class="mx-2" x-data="{ open: {{ request()->routeIs('due-ledger.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    @mouseenter="showTooltip('বাকি খাতা', $el)"
                    @mouseleave="hideTooltip()"
                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg {{ request()->routeIs('due-ledger.*') ? 'bg-primary-800/50 text-white' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                          :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">বাকি খাতা</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open, 'opacity-0 hidden': !sidebarOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="mt-1 ml-6 space-y-1" :class="{ 'hidden': !sidebarOpen }">
                <a href="{{ route('due-ledger.today') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('due-ledger.today') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">আজকের জমা</a>
                <a href="{{ route('due-ledger.due-today') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('due-ledger.due-today') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">আজ জমা দেবে</a>
                <a href="{{ route('due-ledger.all-due') }}" wire:navigate
                   class="block px-4 py-2 text-xs rounded transition-all font-sans {{ request()->routeIs('due-ledger.all-due') ? 'bg-secondary text-white font-bold shadow-sm' : 'text-primary-200 hover:text-white hover:bg-primary-800/40' }}">সব বাকি লিস্ট</a>
            </div>
        </div>
        @endif

        <!-- 6. ক্যাশ খাতা -->
        @if($hasAccess('cash_ledger'))
        <div class="mx-2">
            <a href="{{ route('cash-khata') }}" wire:navigate
               @mouseenter="showTooltip('ক্যাশ খাতা', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('cash-khata') ? 'bg-secondary text-white shadow-sm' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ক্যাশ খাতা</span>
            </a>
        </div>
        @endif

        <!-- 7. লোড খাতা -->
        @if($hasAccess('load_ledger'))
        <div class="mx-2">
            <a href="{{ route('load-khata') }}" wire:navigate
               @mouseenter="showTooltip('লোড খাতা', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('load-khata') ? 'bg-secondary text-white shadow-sm' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }} transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">লোড খাতা</span>
            </a>
        </div>
        @endif

        <!-- 8. আনলোড -->
        @if($hasAccess('unload'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('আনলোড', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">আনলোড</span>
            </a>
        </div>
        @endif

        <!-- 9. ইট খাতা -->
        @if($hasAccess('brick_ledger'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('ইট খাতা', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ইট খাতা</span>
            </a>
        </div>
        @endif

        <!-- 10. খতিয়ান -->
        @if($hasAccess('ledger'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('খতিয়ান', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">খতিয়ান</span>
            </a>
        </div>
        @endif

        <!-- 11. কাস্টমার -->
        @if($hasAccess('customer'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('কাস্টমার', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">কাস্টমার</span>
            </a>
        </div>
        @endif

        <!-- 12. বিক্রি রিপোর্ট -->
        @if($hasAccess('sales_report'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('বিক্রি রিপোর্ট', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">বিক্রি রিপোর্ট</span>
            </a>
        </div>
        @endif

        <!-- 13. ইনভেন্টরি -->
        @if($hasAccess('inventory'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('ইনভেন্টরি', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ইনভেন্টরি</span>
            </a>
        </div>
        @endif

        <!-- 14. ডকুমেন্টস -->
        @if($hasAccess('documents'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('ডকুমেন্টস', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ডকুমেন্টস</span>
            </a>
        </div>
        @endif

        <!-- 15. কাচামাল স্টক -->
        @if($hasAccess('raw_material'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('কাচামাল স্টক', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">কাচামাল স্টক</span>
            </a>
        </div>
        @endif

        <!-- Divider -->
        <div class="h-[1px] bg-primary-800/40 my-4 mx-4"></div>

        <!-- 16. স্টাফ ম্যানেজার -->
        @if($hasAccess('staff'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('স্টাফ ম্যানেজার', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M21 12h-6m6 4h-6"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">স্টাফ ম্যানেজার</span>
            </a>
        </div>
        @endif

        <!-- 17. গাড়ির হিসাব -->
        @if($hasAccess('vehicle_acc'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('গাড়ির হিসাব', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">গাড়ির হিসাব</span>
            </a>
        </div>
        @endif

        <!-- 18. গাড়ি ভাড়া -->
        @if($hasAccess('vehicle_rent'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('গাড়ি ভাড়া', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">গাড়ি ভাড়া</span>
            </a>
        </div>
        @endif

        <!-- 19. দেনা-পাওনা -->
        @if($hasAccess('debts'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('দেনা-পাওনা', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">দেনা-পাওনা</span>
            </a>
        </div>
        @endif

        <!-- 20. অ্যাকাউন্টস -->
        @if($hasAccess('accounts'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('অ্যাকউন্টস', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">অ্যাকউন্টস</span>
            </a>
        </div>
        @endif

        <!-- 21. প্রোডাকশন -->
        @if($hasAccess('production'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('প্রোডাকশন', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">প্রোডাকশন</span>
            </a>
        </div>
        @endif

        <!-- 22. ফোন নাম্বার -->
        @if($hasAccess('phone'))
        <div class="mx-2">
            <a href="#" 
               @mouseenter="showTooltip('ফোন নাম্বার', $el)"
               @mouseleave="hideTooltip()"
               class="flex items-center px-4 py-2.5 rounded-lg text-primary-100 hover:bg-primary-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span class="ml-3 font-medium text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">ফোন নাম্বার</span>
            </a>
        </div>
        @endif



    </div>

    <!-- Sidebar Bottom Fixed Section (Settings & Profile Info) -->
    <div class="p-3 bg-primary-dark border-t border-primary-800 flex-shrink-0 flex items-center justify-between transition-all duration-300 overflow-hidden">
        <!-- Settings Gear Button -->
        <a href="{{ route('settings') }}" wire:navigate
           @mouseenter="showTooltip('সেটিংস', $el)"
           @mouseleave="hideTooltip()"
           :title="!sidebarOpen ? 'সেটিংস' : ''"
           class="p-2 rounded-lg {{ request()->routeIs('settings') ? 'bg-secondary text-white shadow-sm' : 'text-primary-100 hover:bg-primary-sidebar-dark hover:text-white' }} transition-colors duration-150 flex-shrink-0 flex items-center justify-start flex-grow">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="ml-3 text-sm transition-all duration-300 whitespace-nowrap inline-block" 
                  :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 overflow-hidden pointer-events-none'">
                সেটিংস
            </span>
        </a>
    </div>

    <!-- Modern Floating Tooltip Container (Visible when collapsed on hover) -->
    <div 
        x-show="activeTooltip !== '' && !sidebarOpen"
        x-text="activeTooltip"
        :style="`top: ${tooltipTop + 10}px;`"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 translate-x-2 scale-95"
        x-transition:enter-end="opacity-100 translate-x-0 scale-100"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="opacity-100 translate-x-0 scale-100"
        x-transition:leave-end="opacity-0 translate-x-2 scale-95"
        class="absolute left-22 px-3.5 py-2 bg-primary text-white text-xs font-bold rounded-xl shadow-xl whitespace-nowrap z-50 pointer-events-none border border-primary-dark hidden md:block"
        x-cloak>
    </div>

</aside>
