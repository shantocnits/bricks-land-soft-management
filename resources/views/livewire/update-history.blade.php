<div class="space-y-6 font-sans">
    
    <!-- Toast Popup Notification System -->
    <div 
        x-data="{ 
            show: false, 
            message: '', 
            type: 'success' 
        }"
        x-init="
            @if(session()->has('message'))
                message = '{{ session('message') }}';
                show = true;
                setTimeout(() => { show = false; }, 3000);
            @endif
            window.addEventListener('show-toast', event => {
                message = event.detail.message;
                type = event.detail.type || 'success';
                show = true;
                setTimeout(() => { show = false; }, 3000);
            });
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-[99999] p-4 rounded-xl border shadow-2xl flex items-center gap-3 max-w-sm w-[90vw] md:w-auto font-sans"
        :class="type === 'danger' ? 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950/90 dark:border-red-900 dark:text-red-300' : 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-[#034C3C]/95 dark:border-[#034C3C] dark:text-emerald-50'"
        x-cloak
    >
        <!-- Icon -->
        <span class="p-1.5 rounded-lg" :class="type === 'danger' ? 'bg-red-100 text-red-600 dark:bg-red-900/40' : 'bg-emerald-100 text-emerald-600 dark:bg-[#023E31]'">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </span>
        <div class="flex-1">
            <p class="text-xs font-bold font-sans" x-text="message"></p>
        </div>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none ml-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Header Panel -->
    <div class="p-5 bg-red-50/50 dark:bg-slate-900/50 rounded-lg border border-red-100/60 dark:border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-start gap-4">
            <!-- Icon Badge -->
            <div class="p-3 bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-2xl flex-shrink-0 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-red-600 dark:text-red-500 font-sans">
                    আপডেট এবং ডিলিটের হিস্ট্রি
                </h1>
                <p class="text-xs md:text-sm text-red-500/80 dark:text-gray-400 mt-1 font-sans">
                    কোন ইউজার কি আপডেট বা ডিলিট করেছে তা এখানে দেখা যাবে
                </p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Bulk Delete Button (appears when items are selected) -->
            @if(count($selectedLogs) > 0)
                <button 
                    wire:click="deleteSelected" 
                    class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-lg shadow-lg shadow-red-500/10 hover:shadow-red-500/20 active:scale-[0.98] transition-all cursor-pointer"
                >
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>মুছে ফেলুন ({{ count($selectedLogs) }})</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
        
        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden md:block overflow-x-auto min-w-full">
            <table class="min-w-full divide-y divide-gray-150 dark:divide-slate-800 table-fixed">
                <thead class="bg-[#034C3C] text-white">
                    <tr>
                        <th scope="col" class="w-[6%] px-6 py-4 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                        </th>
                        <th scope="col" class="w-[8%] px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ক্র. নং</th>
                        <th scope="col" class="w-[18%] px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ক্ষেত্র</th>
                        <th scope="col" class="w-[50%] px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">বিবরণ</th>
                        <th scope="col" class="w-[11%] px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">ইউজার</th>
                        <th scope="col" class="w-[15%] px-6 py-4 text-left text-xs font-bold font-sans uppercase tracking-wider">তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($logs as $log)
                        <tr class="odd:bg-[#FAF6F4] even:bg-white dark:odd:bg-slate-900 dark:even:bg-slate-800/40 hover:bg-orange-50/20 dark:hover:bg-slate-800/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" wire:model.live="selectedLogs" value="{{ $log->id }}" class="w-4 h-4 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500 dark:text-slate-400 font-sans">
                                {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-rose-600 dark:text-rose-400 font-sans truncate">
                                {{ $log->field }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-amber-900 dark:text-amber-200 font-sans leading-relaxed break-words">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-slate-300 font-sans">
                                {{ $log->user_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400 font-sans">
                                {{ $log->created_at->format('F j, Y g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-slate-500 font-sans font-medium">
                                কোনো ইতিহাস পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Box System / Card View (Hidden on Desktop) -->
        <div class="md:hidden p-4 space-y-4 bg-gray-50/50 dark:bg-slate-900/30">
            <!-- Select All Checkbox for Mobile -->
            <div class="flex items-center justify-between p-3.5 bg-white dark:bg-slate-900 rounded-lg border border-gray-150 dark:border-slate-800 shadow-sm">
                <span class="text-xs font-bold text-gray-700 dark:text-slate-300">সব সিলেক্ট করুন</span>
                <input type="checkbox" wire:model.live="selectAll" class="w-4.5 h-4.5 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
            </div>

            @forelse ($logs as $log)
                <div class="bg-white dark:bg-slate-900 p-4 rounded-lg border border-gray-150 dark:border-slate-800/80 shadow-sm space-y-3 relative hover:border-orange-200 dark:hover:border-slate-700 transition-all">
                    <!-- Checkbox, SL No & Field Badge -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model.live="selectedLogs" value="{{ $log->id }}" class="w-4.5 h-4.5 text-[#034C3C] focus:ring-[#034C3C] rounded border-gray-300 dark:bg-slate-800 dark:border-slate-700 cursor-pointer">
                            <span class="text-xs font-bold text-gray-400 dark:text-slate-500 font-sans">
                                ক্র. নং #{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 border border-rose-100/50">
                                {{ $log->field }}
                            </span>
                        </div>
                        <span class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold font-sans">
                            {{ $log->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="text-xs font-semibold text-amber-950 dark:text-amber-100 font-sans leading-relaxed">
                        {{ $log->description }}
                    </div>

                    <!-- User Information -->
                    <div class="flex items-center justify-between border-t border-gray-100 dark:border-slate-800/60 pt-2.5 text-[11px]">
                        <div class="flex items-center gap-1 text-gray-500 dark:text-slate-400 font-medium">
                            <span>ইউজার:</span>
                            <span class="text-gray-800 dark:text-slate-200 font-bold font-sans">{{ $log->user_name }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-400 dark:text-slate-500 font-sans font-medium bg-white dark:bg-slate-900 rounded-lg border border-gray-150">
                    কোনো ইতিহাস পাওয়া যায়নি।
                </div>
            @endforelse
        </div>

        <!-- Footer / Pagination Panel -->
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 font-sans text-xs md:text-sm">
            <!-- Left Panel: Count & Sort Dropdown next to it -->
            <div class="flex flex-row items-center gap-4 flex-wrap">
                <div class="text-gray-500 dark:text-slate-400 font-semibold font-sans flex items-center gap-1.5 whitespace-nowrap">
                    <span>মোট হিস্ট্রি</span>
                    <span class="text-gray-800 dark:text-white font-bold bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded-full font-sans">{{ $totalCount }}</span>
                    <span>টি</span>
                </div>
                
                <!-- Custom Dropdown following Project Root style -->
                <div x-data="{ open: false, selectedLabel: '{{ $perPage }} হিস্ট্রি / পেজ' }" class="relative inline-block text-left">
                    <button @click="open = !open" type="button" 
                            class="flex items-center justify-between gap-2 px-3.5 py-1.5 bg-emerald-50 dark:bg-emerald-950/20 text-[#034C3C] dark:text-emerald-300 font-semibold rounded-full text-[11px] border border-emerald-200 dark:border-emerald-900/60 focus:outline-none transition-all duration-150 cursor-pointer">
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
                        <button type="button" @click="$wire.set('perPage', 10); selectedLabel = '10 হিস্ট্রি / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">10 হিস্ট্রি / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 15); selectedLabel = '15 হিস্ট্রি / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">15 হিস্ট্রি / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 25); selectedLabel = '25 হিস্ট্রি / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">25 হিস্ট্রি / পেজ</button>
                        <button type="button" @click="$wire.set('perPage', 50); selectedLabel = '50 হিস্ট্রি / পেজ'; open = false" class="w-full text-left px-3.5 py-2 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-emerald-700 dark:hover:text-emerald-400 font-semibold transition-all font-sans">50 হিস্ট্রি / পেজ</button>
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

    <!-- Custom inline styles to force active pagination links match primary color theme -->
    <style>
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
