<div class="space-y-5">

    {{-- Toast Notification --}}
    <template x-teleport="body">
        <div x-data="{ show: false, message: '', timer: null }"
             x-init="
                window.addEventListener('show-toast', e => {
                    let d = e.detail;
                    let msg = '';
                    if (typeof d === 'string') msg = d;
                    else if (d && d.message) msg = d.message;
                    else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                    if (msg) {
                        message = msg;
                        show = false;
                        if (timer) clearTimeout(timer);
                        setTimeout(() => { show = true; timer = setTimeout(() => show = false, 3000); }, 50);
                    }
                });
             "
             @show-toast.window="
                let d = $event.detail;
                let msg = '';
                if (typeof d === 'string') msg = d;
                else if (d && d.message) msg = d.message;
                else if (d && d[0]) msg = typeof d[0] === 'string' ? d[0] : (d[0].message || '');
                if (msg) {
                    message = msg;
                    show = false;
                    if (timer) clearTimeout(timer);
                    $nextTick(() => { show = true; timer = setTimeout(() => show = false, 3000); });
                }
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="-translate-y-10 opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="-translate-y-10 opacity-0 scale-95"
             x-cloak
             class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999999] px-5 py-3 bg-[#034C3C] text-white rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs border border-emerald-400/30">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span x-text="message"></span>
            <button @click="show = false" class="text-white/70 hover:text-white ml-2 cursor-pointer">✕</button>
        </div>
    </template>

    {{-- ========== TOP ACTION BAR ========== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors duration-300">
        
        {{-- Left: New Button --}}
        <div class="flex items-center gap-3">
            <button type="button" wire:click="openCreateModal"
                class="px-4 py-2 bg-[#009669] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                নতুন নম্বর
            </button>

            {{-- Count badge --}}
            <div class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-900/40 rounded-xl text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                মোট: <strong class="font-mono ml-0.5">{{ $contacts->total() }}</strong>
            </div>
        </div>

        {{-- Right: Search --}}
        <div class="w-full sm:w-72 relative">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="নাম, ফোন, ঠিকানা খুঁজুন..."
                   class="w-full pl-3 pr-9 py-2 text-xs bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-white rounded-xl border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-gray-400 font-sans">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ========== TABLE CARD ========== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-[#009669] text-white text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">নাম</th>
                        <th class="py-3 px-4">ঠিকানা</th>
                        <th class="py-3 px-4">পেশা</th>
                        <th class="py-3 px-4">ফোন নম্বর</th>
                        <th class="py-3 px-4 text-center w-24">বাটন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 font-sans">
                    @forelse($contacts as $index => $contact)
                        <tr class="hover:bg-emerald-50/20 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 text-center font-mono font-semibold text-gray-500 dark:text-slate-400 text-xs">
                                {{ $contacts->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-800 dark:text-slate-200">
                                {{ $contact->name }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-slate-400 text-xs">
                                {{ $contact->address ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-xs">
                                @if($contact->profession)
                                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 rounded-full font-medium">
                                        {{ $contact->profession }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-emerald-700 dark:text-emerald-400 font-semibold">
                                {{ $contact->phone ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" wire:click="openEditModal({{ $contact->id }})"
                                        class="p-1.5 rounded-lg text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-all cursor-pointer"
                                        title="সম্পাদনা">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                        wire:click="confirmDelete({{ $contact->id }})"
                                        class="p-1.5 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all cursor-pointer"
                                        title="মুছুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-sm font-medium">কোনো নম্বর পাওয়া যায়নি</p>
                                    <button type="button" wire:click="openCreateModal"
                                        class="text-xs text-emerald-600 hover:underline cursor-pointer">নতুন নম্বর যোগ করুন →</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-slate-800">
            @forelse($contacts as $index => $contact)
                <div class="p-4 space-y-2 hover:bg-emerald-50/20 dark:hover:bg-slate-800/30 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300">{{ mb_substr($contact->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-white text-sm">{{ $contact->name }}</p>
                                @if($contact->profession)
                                    <span class="text-[10px] px-1.5 py-0.5 bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 rounded-full font-medium">{{ $contact->profession }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <button type="button" wire:click="openEditModal({{ $contact->id }})"
                                class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-100 dark:hover:bg-emerald-950/40 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $contact->id }})"
                                class="p-1.5 rounded-lg text-red-500 hover:bg-red-100 dark:hover:bg-red-950/30 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs pl-12">
                        <div>
                            <span class="text-gray-400">ঠিকানা:</span>
                            <span class="font-medium text-gray-700 dark:text-slate-300 ml-1">{{ $contact->address ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">ফোন:</span>
                            <span class="font-mono font-semibold text-emerald-700 dark:text-emerald-400 ml-1">{{ $contact->phone ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 dark:text-slate-500 text-sm">কোনো নম্বর পাওয়া যায়নি।</div>
            @endforelse
        </div>

        {{-- ========== TABLE FOOTER ========== --}}
        @if($contacts->total() > 0)
        <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
            
            {{-- Showing info --}}
            <p class="text-xs text-gray-500 dark:text-slate-400 font-sans whitespace-nowrap">
                Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} results
            </p>

            {{-- Pagination + Per Page --}}
            <div class="flex items-center gap-3">
                
                {{-- Per Page --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 transition-all cursor-pointer">
                        <span>{{ $perPage }} / পেজ</span>
                        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute bottom-full mb-1.5 right-0 z-[999] w-32 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden"
                         x-cloak>
                        <div class="py-1">
                            @foreach([10, 15, 25, 50] as $size)
                            <button type="button" wire:click="$set('perPage', {{ $size }}); open = false" @click="open = false"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold transition-all cursor-pointer
                                        {{ $perPage == $size ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                {{ $size }} রেকর্ড
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pagination Buttons --}}
                <div class="flex items-center gap-1">
                    @if($contacts->onFirstPage())
                        <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                    @else
                        <button wire:click="previousPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                    @endif

                    @php
                        $startPage = max(1, $contacts->currentPage() - 2);
                        $endPage   = min($contacts->lastPage(), $contacts->currentPage() + 2);
                    @endphp
                    @if($startPage > 1)
                        <button wire:click="gotoPage(1)" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">1</button>
                        @if($startPage > 2)<span class="px-1 text-gray-400">...</span>@endif
                    @endif
                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if($page == $contacts->currentPage())
                            <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-[#034C3C] dark:text-emerald-400 font-bold rounded-lg text-xs border border-emerald-200 dark:border-emerald-900 font-mono">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">{{ $page }}</button>
                        @endif
                    @endfor
                    @if($endPage < $contacts->lastPage())
                        @if($endPage < $contacts->lastPage() - 1)<span class="px-1 text-gray-400">...</span>@endif
                        <button wire:click="gotoPage({{ $contacts->lastPage() }})" class="px-2.5 py-1 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg text-xs border border-gray-200 dark:border-slate-700 font-mono cursor-pointer">{{ $contacts->lastPage() }}</button>
                    @endif

                    @if($contacts->hasMorePages())
                        <button wire:click="nextPage" class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-slate-300 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    @else
                        <button disabled class="p-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ========== ADD/EDIT MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showModal') }"
             x-show="open"
             @click.self="$wire.closeModal()"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg border border-gray-200 dark:border-slate-700 shadow-2xl relative overflow-hidden">

                {{-- Modal Header --}}
                <div class="bg-[#009669] px-6 py-4 flex items-center justify-between">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2 font-sans">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $editingId ? 'নম্বর আপডেট করুন' : 'নতুন নম্বর যোগ করুন' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-white/80 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    
                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">নাম <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="পূর্ণ নাম লিখুন"
                               class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Address + Profession --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">ঠিকানা</label>
                            <input type="text" wire:model="address" placeholder="ঠিকানা লিখুন"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">পেশা</label>
                            <input type="text" wire:model="profession" placeholder="পেশা লিখুন"
                                   class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-semibold">
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">ফোন নম্বর</label>
                        <input type="text" wire:model="phone" placeholder="০১XXXXXXXXX"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans font-mono font-semibold">
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 font-sans">নোট (ঐচ্ছিক)</label>
                        <textarea wire:model="notes" rows="2" placeholder="অতিরিক্ত তথ্য..."
                                  class="w-full py-2 px-3 rounded-xl border border-gray-250 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-xs text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-sans resize-none"></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 pb-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                            class="px-5 py-2.5 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer font-sans">
                        বাতিল
                    </button>
                    <button type="button" wire:click="save"
                            class="px-6 py-2.5 bg-[#009669] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer transition-all active:scale-95 font-sans flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $editingId ? 'আপডেট করুন' : 'সেভ করুন' }}
                    </button>
                </div>
            </div>
        </div>
    </template>
    {{-- ========== DELETE CONFIRMATION MODAL ========== --}}
    <template x-teleport="body">
        <div x-data="{ open: @entangle('showDeleteConfirmModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="open = false; $wire.set('showDeleteConfirmModal', false)"
             class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/30 backdrop-blur-xs"
             x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 w-full max-w-xs p-5 flex flex-col items-center gap-3 text-center"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white font-sans">নম্বরটি মুছে ফেলবেন?</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-sans">এই নম্বরটি স্থায়ীভাবে মুছে যাবে।</p>
                </div>
                <div class="flex gap-2.5 w-full justify-center mt-1">
                    <button type="button"
                            @click="open = false; $wire.set('showDeleteConfirmModal', false)"
                            class="px-5 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl cursor-pointer transition-all font-sans">
                        না
                    </button>
                    <button type="button"
                            wire:click="delete"
                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-all active:scale-95 font-sans">
                        হ্যাঁ, ডিলেট করুন
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
