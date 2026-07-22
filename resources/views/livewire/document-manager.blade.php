<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12 font-sans relative">
    
    <!-- Top-Center Floating Toast Notification System (1.5 - 2 sec auto dismiss) -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-init="
            @if(session()->has('message'))
                message = '{{ session('message') }}';
                show = true;
                setTimeout(() => show = false, 2000);
            @endif
            window.addEventListener('show-toast', e => {
                message = e.detail.message;
                type = e.detail.type || 'success';
                show = true;
                setTimeout(() => show = false, 2000);
            });
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         x-cloak
         class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 font-sans font-bold text-xs border backdrop-blur-md"
         :class="type === 'success' ? 'bg-[#034C3C] text-white border-emerald-400/40' : 'bg-rose-600 text-white border-rose-400/40'">
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black"
              :class="type === 'success' ? 'bg-emerald-500/30 text-emerald-300' : 'bg-white/20 text-white'">
            <span x-text="type === 'success' ? '✓' : '✕'"></span>
        </span>
        <span x-text="message"></span>
    </div>

    <div class="w-full space-y-6">
        
        <!-- Header & Breadcrumbs Toolbar (Exact match for Image 2) -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Left: Back Button, Home Button & Breadcrumbs -->
            <div class="flex items-center gap-2 flex-wrap text-xs font-bold">
                <!-- Back Button (<-) -->
                <button type="button" wire:click="navigateToFolder({{ $currentFolder ? $currentFolder->parent_id : null }})"
                        class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-[#034C3C] hover:text-white dark:hover:bg-emerald-600 transition-all cursor-pointer shadow-xs flex items-center justify-center" title="আগের ফোল্ডার (Back)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </button>

                <!-- Home Button -->
                <button type="button" wire:click="navigateToFolder(null)"
                        class="px-3.5 py-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-[#034C3C] hover:text-white dark:hover:bg-emerald-600 transition-all cursor-pointer shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </button>

                <!-- Path Breadcrumbs -->
                @foreach($breadcrumbs as $bc)
                    <span class="text-gray-400 font-bold">/</span>
                    <button type="button" wire:click="navigateToFolder({{ $bc->id }})"
                            class="font-extrabold text-[#034C3C] dark:text-emerald-400 hover:underline">
                        {{ $bc->name }}
                    </button>
                @endforeach
            </div>

            <!-- Right Controls: Search, View Switches, Add Folder & Upload Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                
                <!-- Search Box -->
                <div class="relative w-full sm:w-56">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="খুঁজুন..."
                           class="w-full pl-3 pr-8 py-2 text-xs font-semibold rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500">
                    <span class="absolute right-2.5 top-2.5 text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>

                <!-- View Switchers (Grid, List, Gallery) -->
                <div class="flex items-center gap-1 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl">
                    <!-- Grid View -->
                    <button type="button" wire:click="$set('viewMode', 'grid')" title="গ্রিড ভিউ"
                            class="p-2 rounded-lg transition-all cursor-pointer {{ $viewMode === 'grid' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </button>

                    <!-- List View -->
                    <button type="button" wire:click="$set('viewMode', 'list')" title="লিস্ট ভিউ"
                            class="p-2 rounded-lg transition-all cursor-pointer {{ $viewMode === 'list' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </button>

                    <!-- Gallery View -->
                    <button type="button" wire:click="$set('viewMode', 'gallery')" title="গ্যালারি ভিউ"
                            class="p-2 rounded-lg transition-all cursor-pointer {{ $viewMode === 'gallery' ? 'bg-[#034C3C] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </button>
                </div>

                <!-- Add Folder Button -->
                <button type="button" wire:click="openFolderModal()"
                        class="px-3.5 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-800 dark:text-slate-200 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-700 transition-all cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    ফোল্ডার
                </button>

                <!-- Upload File Button -->
                <button type="button" wire:click="openUploadModal()"
                        class="px-4 py-2 bg-[#034C3C] hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    আপলোড
                </button>
            </div>
        </div>

        <!-- Main Display Container -->
        <div class="bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm min-h-[420px]">
            
            @if($folders->count() === 0 && $files->count() === 0)
                <!-- Empty State (Exact match for Image 2) -->
                <div class="flex flex-col items-center justify-center py-20 text-center space-y-3">
                    <div class="p-4 bg-gray-100 dark:bg-slate-800/80 rounded-3xl text-gray-300 dark:text-slate-600">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-400 dark:text-slate-500">এই ফোল্ডারটি খালি</span>
                </div>
            @else

                <!-- GRID VIEW -->
                @if($viewMode === 'grid')
                    <div class="space-y-6">
                        <!-- Folders Grid -->
                        @if($folders->count() > 0)
                            <div>
                                <h4 class="text-xs font-extrabold text-gray-500 uppercase tracking-wider mb-3">ফোল্ডারসমূহ ({{ $folders->count() }})</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach($folders as $f)
                                        <div class="group relative bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-2xl p-4 transition-all duration-200 hover:shadow-md cursor-pointer"
                                             wire:click="navigateToFolder({{ $f->id }})">
                                            <div class="flex items-center justify-between">
                                                <div class="p-2.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-xl">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button type="button" wire:click.stop="openFolderModal({{ $f->id }})" title="এডিট"
                                                            class="text-gray-500 hover:text-emerald-600 p-1 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <button type="button" wire:confirm="ফোল্ডারটি মুছে ফেলতে চান?" wire:click.stop="deleteFolder({{ $f->id }})" title="মুছে ফেলুন"
                                                            class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <h5 class="font-extrabold text-xs text-gray-800 dark:text-white truncate" title="{{ $f->name }}">{{ $f->name }}</h5>
                                                <span class="text-[10px] text-gray-400 font-semibold block mt-0.5">{{ $f->files()->count() }} টি ফাইল</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Files Grid -->
                        @if($files->count() > 0)
                            <div>
                                <h4 class="text-xs font-extrabold text-gray-500 uppercase tracking-wider mb-3">ফাইলসমূহ ({{ $files->count() }})</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($files as $file)
                                        <div class="group relative bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 transition-all duration-200 hover:shadow-md flex flex-col justify-between space-y-3">
                                            <div>
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="p-2 bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 rounded-xl">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <button type="button" wire:click="openFileEditModal({{ $file->id }})" title="তথ্য এডিট"
                                                                class="text-gray-500 hover:text-emerald-600 p-1 cursor-pointer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <button type="button" wire:confirm="ফাইলটি মুছে ফেলতে চান?" wire:click="deleteFile({{ $file->id }})" title="ডিলিট"
                                                                class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <h5 class="font-extrabold text-xs text-gray-900 dark:text-white truncate" title="{{ $file->title }}">{{ $file->title }}</h5>
                                                <span class="text-[10px] text-gray-400 font-mono block mt-0.5 uppercase">{{ $file->file_type }} • {{ number_format($file->file_size / 1024, 1) }} KB</span>
                                                @if($file->description)
                                                    <p class="text-[11px] text-gray-500 dark:text-slate-400 line-clamp-2 mt-1">{{ $file->description }}</p>
                                                @endif
                                            </div>
                                            
                                            <!-- View & Download Buttons (Stream Route Fixes 403 Forbidden) -->
                                            <div class="pt-2 border-t border-gray-200/50 dark:border-slate-800/50 flex items-center justify-between text-xs font-bold">
                                                <a href="{{ route('documents.stream', $file->id) }}" target="_blank" rel="noopener noreferrer"
                                                   class="px-2.5 py-1 bg-sky-50 dark:bg-slate-800 text-sky-600 dark:text-sky-400 hover:bg-sky-600 hover:text-white dark:hover:bg-sky-600 dark:hover:text-white rounded-lg transition-all flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    ভিউ
                                                </a>
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" download="{{ $file->file_name }}"
                                                   class="px-2.5 py-1 bg-emerald-50 dark:bg-slate-800 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-700 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white rounded-lg transition-all flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    ডাউনলোড
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                <!-- LIST VIEW -->
                @elseif($viewMode === 'list')
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-gray-200 dark:border-slate-800">
                            <thead>
                                <tr class="bg-[#034C3C] text-white text-[11px] font-bold uppercase">
                                    <th class="py-3.5 px-4">নাম / টাইটেল</th>
                                    <th class="py-3.5 px-4 text-center">টাইপ</th>
                                    <th class="py-3.5 px-4 text-right">সাইজ</th>
                                    <th class="py-3.5 px-4 text-center">তারিখ</th>
                                    <th class="py-3.5 px-4 text-center">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-xs font-semibold">
                                @foreach($folders as $f)
                                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/50 cursor-pointer" wire:click="navigateToFolder({{ $f->id }})">
                                        <td class="py-3.5 px-4 font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                            {{ $f->name }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-gray-400">ফোল্ডার</td>
                                        <td class="py-3.5 px-4 text-right font-mono">—</td>
                                        <td class="py-3.5 px-4 text-center text-gray-500">{{ $f->created_at->format('d-m-Y') }}</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" wire:click.stop="openFolderModal({{ $f->id }})" class="text-emerald-600 font-bold hover:underline cursor-pointer">এডিট</button>
                                                <button type="button" wire:confirm="ফোল্ডারটি মুছে ফেলতে চান?" wire:click.stop="deleteFolder({{ $f->id }})" class="text-rose-500 font-bold hover:underline cursor-pointer">ডিলিট</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($files as $file)
                                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/50">
                                        <td class="py-3.5 px-4 font-bold text-gray-800 dark:text-white">
                                            {{ $file->title }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-mono uppercase text-sky-600 font-bold">{{ $file->file_type }}</td>
                                        <td class="py-3.5 px-4 text-right font-mono text-gray-500">{{ number_format($file->file_size / 1024, 1) }} KB</td>
                                        <td class="py-3.5 px-4 text-center text-gray-500">{{ $file->created_at->format('d-m-Y') }}</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2 font-bold">
                                                <a href="{{ route('documents.stream', $file->id) }}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1 bg-sky-50 dark:bg-slate-800 text-sky-600 dark:text-sky-400 hover:bg-sky-600 hover:text-white dark:hover:bg-sky-600 dark:hover:text-white rounded-lg transition-all">ভিউ</a>
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" download="{{ $file->file_name }}" class="px-2.5 py-1 bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-700 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white rounded-lg transition-all">ডাউনলোড</a>
                                                <button type="button" wire:click="openFileEditModal({{ $file->id }})" class="px-2.5 py-1 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 hover:bg-emerald-600 hover:text-white rounded-lg transition-all cursor-pointer">এডিট</button>
                                                <button type="button" wire:confirm="ফাইলটি মুছে ফেলতে চান?" wire:click="deleteFile({{ $file->id }})" class="px-2.5 py-1 bg-rose-50 dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white rounded-lg transition-all cursor-pointer">ডিলিট</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                <!-- GALLERY VIEW -->
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($files as $file)
                            <div class="bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 flex flex-col items-center text-center space-y-3 relative group">
                                <button type="button" wire:click="openFileEditModal({{ $file->id }})" title="এডিট"
                                        class="absolute top-2 right-2 p-1 text-gray-400 hover:text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if(in_array(strtolower($file->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                                    <img src="{{ Storage::url($file->file_path) }}" alt="{{ $file->title }}" class="h-36 w-full object-cover rounded-xl border border-gray-200 dark:border-slate-800">
                                @else
                                    <div class="h-36 w-full bg-emerald-50 dark:bg-slate-900 rounded-xl flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-extrabold font-mono text-xl uppercase">
                                        {{ $file->file_type }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="font-extrabold text-xs text-gray-800 dark:text-white truncate max-w-[180px]">{{ $file->title }}</h5>
                                    <span class="text-[10px] text-gray-400 font-mono block mt-0.5">{{ number_format($file->file_size / 1024, 1) }} KB</span>
                                </div>
                                <div class="flex items-center gap-2 w-full font-bold text-xs">
                                    <a href="{{ route('documents.stream', $file->id) }}" target="_blank" rel="noopener noreferrer"
                                       class="flex-1 py-1.5 bg-sky-50 dark:bg-slate-800 text-sky-600 dark:text-sky-400 hover:bg-sky-600 hover:text-white rounded-lg transition-all">
                                        ভিউ
                                    </a>
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" download="{{ $file->file_name }}"
                                       class="flex-1 py-1.5 bg-[#034C3C] text-white hover:bg-emerald-800 rounded-lg transition-all">
                                        ডাউনলোড
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @endif

        </div>

    </div>

    <!-- Modal 1: Create / Edit Folder Modal -->
    @if($showFolderModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showFolderModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-sm p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">
                        {{ $editingFolderId ? 'ফোল্ডার এডিট করুন' : 'নতুন ফোল্ডার' }}
                    </h3>
                    <button type="button" wire:click="$set('showFolderModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">ফোল্ডারের নাম *</label>
                    <input type="text" wire:model="folderName" placeholder="উদা: লাইসেন্স / ভাউচার"
                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                    @error('folderName') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showFolderModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">বাতিল</button>
                    <button type="button" wire:click="saveFolder()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] text-white rounded-xl font-bold text-xs cursor-pointer">
                        <span wire:loading.remove wire:target="saveFolder">সংরক্ষণ করুন</span>
                        <span wire:loading wire:target="saveFolder">সংরক্ষণ হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Upload File Modal -->
    @if($showUploadModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showUploadModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">ডকুমেন্ট আপলোড</h3>
                    <button type="button" wire:click="$set('showUploadModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ডকুমেন্ট টাইটেল *</label>
                        <input type="text" wire:model="fileTitle" placeholder="উদা: জমি রেজিস্ট্রি দলিল / ভাউচার"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        @error('fileTitle') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ফাইল সিলেক্ট করুন (যেকোনো ফরম্যাট - Max 50MB) *</label>
                        <input type="file" wire:model="uploadedFile"
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none font-semibold cursor-pointer">
                        @error('uploadedFile') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">বিবরণ / নোট</label>
                        <textarea wire:model="fileDescription" rows="2" placeholder="সংক্ষিপ্ত নোট..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showUploadModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">বাতিল</button>
                    <button type="button" wire:click="saveFile()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] text-white rounded-xl font-bold text-xs cursor-pointer">
                        <span wire:loading.remove wire:target="saveFile">আপলোড করুন</span>
                        <span wire:loading wire:target="saveFile">আপলোড হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 3: Edit File Modal -->
    @if($showFileEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             wire:click.self="$set('showFileEditModal', false)">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-150 dark:border-slate-800 w-full max-w-md p-6 space-y-4"
                 wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-white">ডকুমেন্ট তথ্য এডিট</h3>
                    <button type="button" wire:click="$set('showFileEditModal', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">ডকুমেন্ট টাইটেল *</label>
                        <input type="text" wire:model="editFileTitle" placeholder="টাইটেল..."
                               class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold">
                        @error('editFileTitle') <span class="text-rose-500 text-[10px] font-bold mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-300 mb-1">বিবরণ / নোট</label>
                        <textarea wire:model="editFileDescription" rows="3" placeholder="সংক্ষিপ্ত নোট..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-gray-800 dark:text-white focus:outline-none focus:border-emerald-500 font-semibold"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showFileEditModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-200 rounded-xl font-bold text-xs cursor-pointer">বাতিল</button>
                    <button type="button" wire:click="saveFileEdit()" wire:loading.attr="disabled" class="px-4 py-2 bg-[#034C3C] text-white rounded-xl font-bold text-xs cursor-pointer">
                        <span wire:loading.remove wire:target="saveFileEdit">আপডেট করুন</span>
                        <span wire:loading wire:target="saveFileEdit">আপডেট হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
