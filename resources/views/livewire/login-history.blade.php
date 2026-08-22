<div class="space-y-6 font-sans">
    
    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp

    <!-- Header Panel -->
    <div class="p-5 bg-red-50/50 dark:bg-slate-900/50 rounded-lg border border-red-100/60 dark:border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-xl md:text-2xl font-bold text-red-600 dark:text-red-500 font-sans tracking-wide">
                ইউজারের লগইন - লগআউট রেকর্ড
            </h1>
        </div>
        
        <!-- Action Buttons (Only visible for Admin) -->
        @if($isAdmin && count($selectedLogs) > 0)
            <div class="flex items-center justify-center md:justify-end">
                <button 
                    type="button"
                    wire:click="confirmDeleteSelected" 
                    class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-lg shadow-lg shadow-red-500/10 hover:shadow-red-500/20 active:scale-[0.98] transition-all cursor-pointer"
                >
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>মুছে ফেলুন ({{ count($selectedLogs) }})</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Table Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
        
        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden md:block overflow-x-auto min-w-full">
            <table class="min-w-full divide-y divide-gray-150 dark:divide-slate-800">
                <thead class="bg-[#034C3C] text-white">
                    <tr>
                        @if($isAdmin)
                        <th scope="col" class="w-[6%] px-6 py-4 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                        </th>
                        @endif
                        <th scope="col" class="{{ $isAdmin ? 'w-[8%]' : 'w-[10%]' }} px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ক্র. নং</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ধরণ</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ইউজার</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">সময়</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ডিভাইস</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">আইপি</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($logs as $log)
                        <tr class="odd:bg-gray-50/30 even:bg-white dark:odd:bg-slate-900 dark:even:bg-slate-800/40 hover:bg-emerald-50/10 dark:hover:bg-slate-800/80 transition-colors">
                            @if($isAdmin)
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" wire:model.live="selectedLogs" value="{{ $log->id }}" class="w-4 h-4 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500 dark:text-slate-400 font-sans">
                                {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold font-sans">
                                @if (strtolower($log->type) === 'login')
                                    <span class="text-emerald-600 dark:text-emerald-400">Login</span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400">Logout</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-slate-300 font-sans">
                                {{ $log->user_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600 dark:text-teal-400 font-sans">
                                {{ $log->time ? $log->time->format('F j, Y g:i:s A') : $log->created_at->format('F j, Y g:i:s A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600 dark:text-slate-400 font-sans">
                                {{ $log->device }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600 dark:text-slate-400 font-sans">
                                {{ $log->ip }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? '7' : '6' }}" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-slate-500 font-sans font-medium">
                                কোনো লগইন রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Box System / Card View (Hidden on Desktop) -->
        <div class="md:hidden p-4 space-y-4 bg-gray-50/50 dark:bg-slate-900/30">
            @if($isAdmin)
            <!-- Select All Checkbox for Mobile -->
            <div class="flex items-center justify-between p-3.5 bg-white dark:bg-slate-900 rounded-lg border border-gray-150 dark:border-slate-800 shadow-sm">
                <span class="text-xs font-bold text-gray-700 dark:text-slate-300">সব সিলেক্ট করুন</span>
                <input type="checkbox" wire:model.live="selectAll" class="w-4.5 h-4.5 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
            </div>
            @endif

            @forelse ($logs as $log)
                <div class="bg-white dark:bg-slate-900 p-4 rounded-lg border border-gray-150 dark:border-slate-800/80 shadow-sm space-y-3 relative hover:border-emerald-200 dark:hover:border-slate-700 transition-all">
                    <!-- Checkbox & Type Badge -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($isAdmin)
                            <input type="checkbox" wire:model.live="selectedLogs" value="{{ $log->id }}" class="w-4.5 h-4.5 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                            @endif
                            <span class="text-xs font-bold text-gray-400 dark:text-slate-500 font-sans">
                                ক্র. নং #{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                            </span>
                            @if (strtolower($log->type) === 'login')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100/50">
                                    Login
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-100/50">
                                    Logout
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-teal-600 dark:text-teal-400 font-bold font-sans">
                            {{ $log->time ? $log->time->format('d M, h:i:s A') : $log->created_at->format('d M, h:i:s A') }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-2 text-xs font-semibold border-t border-gray-100 dark:border-slate-800/60 pt-2.5">
                        <div class="flex items-center gap-1 text-gray-500 dark:text-slate-400 font-medium">
                            <span>ইউজার:</span>
                            <span class="text-gray-800 dark:text-slate-200 font-bold font-sans">{{ $log->user_name }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-gray-500 dark:text-slate-400 font-medium justify-end">
                            <span>ডিভাইস:</span>
                            <span class="text-gray-800 dark:text-slate-200 font-bold font-sans">{{ $log->device }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-gray-500 dark:text-slate-400 font-medium col-span-2 mt-1">
                            <span>আইপি:</span>
                            <span class="text-gray-800 dark:text-slate-200 font-bold font-sans">{{ $log->ip }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-400 dark:text-slate-500 font-sans font-medium bg-white dark:bg-slate-900 rounded-lg border border-gray-150">
                    কোনো লগইন রেকর্ড পাওয়া যায়নি।
                </div>
            @endforelse
        </div>

        <!-- Footer / Pagination Panel -->
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 font-sans text-xs md:text-sm">
            <!-- Left Panel: Count & Sort Dropdown next to it -->
            <div class="flex flex-row items-center gap-4 flex-wrap">
                <div class="text-gray-500 dark:text-slate-400 font-semibold font-sans flex items-center gap-1.5 whitespace-nowrap">
                    <span>মোট রেকর্ড</span>
                    <span class="text-gray-800 dark:text-white font-bold bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded-full font-sans">{{ $totalCount }}</span>
                    <span>টি</span>
                </div>
                
                <!-- Custom Dropdown following Project Root style -->
                <div x-data="{ open: false, selectedLabel: '{{ $perPage }} রেকর্ড / পেজ' }" class="relative inline-block text-left">
                    <button @click="open = !open" type="button" 
                            class="flex items-center justify-between gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/20 text-[#034C3C] dark:text-emerald-300 font-semibold rounded-full text-[11px] border border-emerald-200 dark:border-emerald-900/60 focus:outline-none transition-all duration-150 cursor-pointer">
                        <span x-text="selectedLabel" class="font-sans"></span>
                        <svg class="w-3 h-3 transition-transform duration-200 text-emerald-700 dark:text-emerald-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 bottom-full mb-1.5 w-36 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-55 text-[11px] overflow-hidden"
                         x-cloak>
                        <button type="button" @click="$wire.set('perPage', 10); selectedLabel = '10 রেকর্ড / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">10 রেকর্ড / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 15); selectedLabel = '15 রেকর্ড / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">15 রেকর্ড / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 25); selectedLabel = '25 রেকর্ড / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">25 রেকর্ড / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 50); selectedLabel = '50 রেকর্ড / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">50 রেকর্ড / পেজ</button>
                    </div>
                </div>
            </div>
            
            <!-- Right Panel: Results & Pagination Controls -->
            <div class="flex items-center gap-6 justify-between lg:justify-end w-full lg:w-auto flex-wrap sm:flex-nowrap">
                <div class="text-xs text-gray-500 dark:text-slate-400 font-semibold font-sans whitespace-nowrap mr-2">
                    showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results (পেজ নং {{ $logs->currentPage() }})
                </div>

                <!-- Custom Pagination Control Buttons wrapped with primary active color theme styles -->
                <div class="pagination-primary">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>

    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteConfirmModal)
    <div class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-data
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-gray-150 dark:border-slate-800 text-center font-sans">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-2">আপনি কি নিশ্চিত?</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                আপনি নির্বাচন করা {{ count($selectedLogs) }} টি লগ রেকর্ড মুছে ফেলতে চান? এই রেকর্ডগুলো আর ফিরিয়ে আনা যাবে না।
            </p>
            <div class="flex items-center justify-center gap-3">
                <button type="button"
                        wire:click="deleteSelected"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                    হ্যাঁ, ডিলেট করুন
                </button>
                <button type="button"
                        wire:click="$set('showDeleteConfirmModal', false)"
                        class="px-5 py-2 bg-gray-200 dark:bg-slate-800 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs rounded-xl transition-all cursor-pointer">
                    না
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom inline styles to force active pagination links match primary color theme -->
    <style data-navigate-once>
        .pagination-primary nav span[aria-current="page"] > span,
        .pagination-primary nav span[aria-current="page"] > a {
            background-color: #034C3C !important;
            color: #ffffff !important;
            border-color: #034C3C !important;
        }
        .pagination-primary nav a {
            color: #034C3C !important;
            transition: all 0.2s ease;
        }
        .pagination-primary nav a:hover {
            background-color: #ecfdf5 !important;
        }
        .pagination-primary nav p {
            display: none !important;
        }
    </style>

</div>
