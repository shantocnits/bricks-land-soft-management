<div class="space-y-6 font-sans">

    <!-- Header Title (Matches User Management style exactly) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-sans">সফটওয়্যার সেটিংস ও কনফিগারেশন</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-sans">প্রতিষ্ঠানের বিবরণ, শ্রেণি ও রেট, খতিয়ান, ইউজার ম্যানেজমেন্ট, প্রিন্টার, স্টক এবং এসএমএস সেটিংস নিয়ন্ত্রণ করুন।</p>
        </div>
    </div>

    <!-- Main Container: Mobile Top-Horizontal, Desktop Left-Sidebar Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        <!-- Mobile Horizontal Tab Menu (Scrollbar visible) -->
        <div class="md:hidden col-span-1 w-full overflow-x-auto whitespace-nowrap pb-3 border-b border-gray-150 dark:border-slate-800/80 scrollbar-thin scrollbar-thumb-emerald-600 dark:scrollbar-thumb-emerald-700 scrollbar-track-transparent">
            <div class="flex gap-2 px-1">
                <!-- 1. ডাটার তথ্য -->
                <button type="button" wire:click="setTab('profile')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'profile' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18v18H3V3z"/>
                    </svg>
                    <span>ডাটার তথ্য</span>
                </button>

                <!-- 2. শ্রেণি এবং রেট -->
                <button type="button" wire:click="setTab('category')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'category' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.16 3.659A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                    </svg>
                    <span>শ্রেণি এবং রেট</span>
                </button>

                <!-- 3. খতিয়ান অ্যাড -->
                <button type="button" wire:click="setTab('ledger')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'ledger' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span>খতিয়ান অ্যাড</span>
                </button>

                <!-- 4. সফটওয়্যার ইউজার -->
                <button type="button" wire:click="setTab('user')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'user' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span>সফটওয়্যার ইউজার</span>
                </button>

                <!-- 5. পাসওয়ার্ড পরিবর্তন -->
                <button type="button" wire:click="setTab('password')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'password' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                    <span>পাসওয়ার্ড পরিবর্তন</span>
                </button>

                <!-- 6. ইউজার লিমিট -->
                <button type="button" wire:click="setTab('limit')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'limit' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <span>ইউজার লিমিট</span>
                </button>

                <!-- 7. ইউজার পারমিশন -->
                <button type="button" wire:click="setTab('permission')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'permission' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <span>ইউজার পারমিশন</span>
                </button>

                <!-- 8. প্রিন্টার সেটিংস -->
                <button type="button" wire:click="setTab('printer')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'printer' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.25 2.25H8.59a2.25 2.25 0 01-2.25-2.25M17.66 18l.128-1.28A2.25 2.25 0 0015.542 14.5H8.458a2.25 2.25 0 00-2.248 2.22L6.34 18M12 9V3m0 0L8.25 6M12 3l3.75 3"/>
                    </svg>
                    <span>প্রিন্টার সেটিংস</span>
                </button>

                <!-- 9. স্টক সেটিংস -->
                <button type="button" wire:click="setTab('stock')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'stock' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span>স্টক সেটিংস</span>
                </button>

                <!-- 10. এসএমএস সেটিংস -->
                <button type="button" wire:click="setTab('sms')"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'sms' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-slate-900 border border-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                    </svg>
                    <span>এসএমএস সেটিংস</span>
                </button>
            </div>
        </div>

        <!-- Desktop Left Sidebar: 3 columns width (grid cols 12) -->
        <aside class="hidden md:block md:col-span-3 sticky top-[84px] z-10 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-2xl p-4 h-fit shadow-sm">
            <nav class="flex flex-col gap-1.5">
                <!-- 1. ডাটার তথ্য -->
                <button type="button" wire:click="setTab('profile')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'profile' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18v18H3V3z"/>
                    </svg>
                    <span>ডাটার তথ্য</span>
                </button>

                <!-- 2. শ্রেণি এবং রেট -->
                <button type="button" wire:click="setTab('category')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'category' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.16 3.659A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                    </svg>
                    <span>শ্রেণি এবং রেট</span>
                </button>

                <!-- 3. খতিয়ান অ্যাড -->
                <button type="button" wire:click="setTab('ledger')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'ledger' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span>খতিয়ান অ্যাড</span>
                </button>

                <!-- 4. সফটওয়্যার ইউজার -->
                <button type="button" wire:click="setTab('user')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'user' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span>সফটওয়্যার ইউজার</span>
                </button>

                <!-- 5. পাসওয়ার্ড পরিবর্তন -->
                <button type="button" wire:click="setTab('password')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'password' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                    <span>পাসওয়ার্ড পরিবর্তন</span>
                </button>

                <!-- 6. ইউজার লিমিট -->
                <button type="button" wire:click="setTab('limit')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'limit' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <span>ইউজার লিমিট</span>
                </button>

                <!-- 7. ইউজার পারমিশন -->
                <button type="button" wire:click="setTab('permission')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'permission' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <span>ইউজার পারমিশন</span>
                </button>

                <!-- 8. প্রিন্টার সেটিংস -->
                <button type="button" wire:click="setTab('printer')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'printer' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.25 2.25H8.59a2.25 2.25 0 01-2.25-2.25M17.66 18l.128-1.28A2.25 2.25 0 0015.542 14.5H8.458a2.25 2.25 0 00-2.248 2.22L6.34 18M12 9V3m0 0L8.25 6M12 3l3.75 3"/>
                    </svg>
                    <span>প্রিন্টার সেটিংস</span>
                </button>

                <!-- 9. স্টক সেটিংস -->
                <button type="button" wire:click="setTab('stock')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'stock' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span>স্টক সেটিংস</span>
                </button>

                <!-- 10. এসএমএস সেটিংস -->
                <button type="button" wire:click="setTab('sms')"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all border-0 {{ $activeTab === 'sms' ? 'bg-primary text-white shadow-md' : 'bg-transparent text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800/50 hover:text-emerald-700 dark:hover:text-emerald-400' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                    </svg>
                    <span>এসএমএস সেটিংস</span>
                </button>
            </nav>
        </aside>

        <!-- Right Content Area: 9 columns width (grid cols 12) -->
        <div class="col-span-1 md:col-span-9 min-w-0 bg-transparent">
            <!-- Smooth fade-in transition -->
            <div class="animate-settings-fade" wire:key="settings-tab-{{ $activeTab }}">
                @switch($activeTab)
                    @case('profile')
                        <livewire:settings.profile-info wire:key="subtab-profile-info" />
                        @break
                    @case('category')
                        <livewire:settings.category-rate wire:key="subtab-category-rate" />
                        @break
                    @case('ledger')
                        <livewire:settings.ledger-add wire:key="subtab-ledger-add" />
                        @break
                    @case('user')
                        <livewire:settings.user-management wire:key="subtab-user-management" />
                        @break
                    @case('permission')
                        <livewire:settings.user-permission wire:key="subtab-user-permission" />
                        @break
                    @case('password')
                        <livewire:settings.password-change wire:key="subtab-password-change" />
                        @break
                    @case('limit')
                        <livewire:settings.user-limit wire:key="subtab-user-limit" />
                        @break
                    @case('printer')
                        <livewire:settings.printer-settings wire:key="subtab-printer-settings" />
                        @break
                    @case('stock')
                        <livewire:settings.stock-settings wire:key="subtab-stock-settings" />
                        @break
                    @case('sms')
                        <livewire:settings.sms-settings wire:key="subtab-sms-settings" />
                        @break
                @endswitch
            </div>
        </div>

    </div>
</div>
