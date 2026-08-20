<div class="font-sans" x-data="{
    modalOpen: false,
    videoUrl: '',
    videoTitle: '',
    openVideo(url, title) {
        this.videoUrl = url;
        this.videoTitle = title;
        this.modalOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeVideo() {
        this.modalOpen = false;
        this.videoUrl = '';
        document.body.style.overflow = '';
    }
}">

    <!-- Toast Popup -->
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-init="window.addEventListener('show-toast', e => { message = e.detail.message; type = e.detail.type || 'success'; show = true; setTimeout(() => show = false, 3000); })"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-[99999] p-4 rounded-xl border shadow-2xl flex items-center gap-3 max-w-sm w-[90vw] md:w-auto bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-[#034C3C]/95 dark:border-[#034C3C] dark:text-emerald-50"
        x-cloak>
        <span class="p-1.5 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-[#023E31]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </span>
        <p class="text-xs font-bold flex-1" x-text="message"></p>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600 cursor-pointer ml-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Video Fullscreen Modal -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="closeVideo()"
        @keydown.escape.window="closeVideo()"
        class="fixed inset-0 z-[9999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 md:p-8"
        x-cloak>

        <!-- Modal Box -->
        <div
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative bg-black rounded-2xl shadow-2xl overflow-hidden w-full max-w-5xl"
            @click.stop>

            <!-- Top bar -->
            <div class="flex items-center justify-between px-5 py-3 bg-gray-900/90 backdrop-blur border-b border-white/10">
                <span class="text-white font-bold text-sm font-sans truncate" x-text="videoTitle"></span>
                <button @click="closeVideo()" class="ml-4 flex-shrink-0 p-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Video Embed -->
            <div class="relative w-full" style="padding-top: 56.25%;">
                <iframe
                    x-bind:src="modalOpen ? videoUrl + '?autoplay=1&rel=0' : ''"
                    class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="space-y-6">

        <!-- Header Banner -->
        <div class="text-center p-5 bg-red-50/60 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 rounded-xl">
            <h1 class="text-lg md:text-xl font-extrabold text-red-600 dark:text-red-400 font-sans tracking-wide">
                সফটওয়্যারটি কি ভাবে ব্যবহার করতে হবে সে সংক্রান্ত ভিডিও
            </h1>
        </div>

        <!-- Video List -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden divide-y divide-gray-100 dark:divide-slate-800">

            @php
            $videos = [
                [
                    'id'          => 'M7lc1UVf-VE',
                    'title'       => 'আমাদের এই সফটওয়্যারের পুরনো ভার্সন এর ভিডিও',
                    'subtitle'    => 'Bricks Land | Codenext IT',
                    'thumbnail'   => 'https://img.youtube.com/vi/M7lc1UVf-VE/hqdefault.jpg',
                    'badge'       => 'A - Z',
                    'badge_color' => 'bg-orange-400',
                ],
                [
                    'id'          => 'tgbNymZ7vqY',
                    'title'       => 'সফটওয়্যার এর ভিডিও',
                    'subtitle'    => 'Bricks Land | Codenext IT',
                    'thumbnail'   => 'https://img.youtube.com/vi/tgbNymZ7vqY/hqdefault.jpg',
                    'badge'       => 'A - Z',
                    'badge_color' => 'bg-orange-400',
                ],
                [
                    'id'          => 'M7lc1UVf-VE',
                    'title'       => 'আমাদের এই সফটওয়্যারের পুরনো ভার্সন এর ভিডিও',
                    'subtitle'    => 'Bricks Land | Codenext IT',
                    'thumbnail'   => 'https://img.youtube.com/vi/M7lc1UVf-VE/hqdefault.jpg',
                    'badge'       => 'A - Z',
                    'badge_color' => 'bg-orange-400',
                ],
            ];
            @endphp

            @foreach ($videos as $video)
                <div
                    @click="openVideo('https://www.youtube.com/embed/{{ $video['id'] }}', '{{ addslashes($video['title']) }}')"
                    class="group flex items-center gap-4 p-4 md:p-5 cursor-pointer hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-all duration-150 select-none">

                    <!-- Thumbnail -->
                    <div class="relative flex-shrink-0 w-28 md:w-36 aspect-video rounded-xl overflow-hidden shadow-md border border-gray-100 dark:border-slate-700">
                        <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='https://placehold.co/320x180/1a1a2e/ffffff?text=Video'">
                        <!-- Play Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/20 transition-colors">
                            <div class="w-9 h-9 md:w-11 md:h-11 bg-red-600 group-hover:bg-red-500 rounded-full flex items-center justify-center shadow-lg transition-all group-hover:scale-110">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Channel name overlay -->
                        <div class="absolute bottom-0 left-0 right-0 px-2 py-1 bg-black/60 backdrop-blur-sm">
                            <p class="text-[9px] text-white/90 font-medium font-sans truncate">{{ $video['subtitle'] }}</p>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm md:text-base font-bold text-gray-800 dark:text-white font-sans group-hover:text-[#034C3C] dark:group-hover:text-emerald-400 transition-colors leading-snug">
                            {{ $video['title'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-sans">{{ $video['subtitle'] }}</p>
                        <div class="mt-2.5">
                            <span class="{{ $video['badge_color'] }} text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full font-sans tracking-wide">
                                {{ $video['badge'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex flex-shrink-0 w-8 h-8 items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-950/30 transition-colors">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
