<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300 pb-12">
    <!-- Page Header Bar -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 px-4 sm:px-6 py-3 rounded-lg flex items-center justify-between gap-3 transition-colors duration-300">
        <div>
            <h2 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">সাধারণ জিজ্ঞাসা</h2>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-sans mt-0.5 font-semibold">কেনার আগে যেসব প্রশ্ন বেশি করা হয়—সংক্ষিপ্ত, পরিষ্কার উত্তর।</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="w-full max-w-4xl mx-auto mt-10 py-6">
        <!-- FAQ Hero Header -->
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-full text-xs font-bold font-sans">
                ✨ Pre-Sales FAQ
            </span>
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-4 tracking-tight">সাধারণ জিজ্ঞাসা</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">কেনার আগে যেসব প্রশ্ন বেশি করা হয়—সংক্ষিপ্ত, পরিষ্কার উত্তর।</p>

            <!-- Feature Badges -->
            <div class="flex flex-wrap items-center justify-center gap-2 mt-6 pb-6">
                <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[11px] font-bold">মোবাইল-ফ্রেন্ডলি UI</span>
                <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[11px] font-bold">অনলাইন-অনলি</span>
                <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[11px] font-bold">পারমিশন কন্ট্রোল</span>
                <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[11px] font-bold">এডিট/ডিলিট + হিস্ট্রি</span>
                <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[11px] font-bold">ইন্সটলেশন ফি ৫০% ডিসকাউন্ট</span>
            </div>
        </div>

        <!-- FAQ List (Accordion) -->
        <div x-data="{ active: 1 }" class="space-y-4">
            
            <!-- Item 1 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 1 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 1 ? null : 1)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">অ্যাপটি কি মোবাইলে ব্যবহার করা যাবে?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-bold font-sans">ব্যবহার</span>
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">মোবাইল</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 1" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> জি, পুরোপুরি মোবাইল-ফ্রেন্ডলি। Android/iPhone-এর ব্রাউজার (Chrome/Safari) থেকেই ব্যবহার করতে পারবেন—ল্যাপটপ বা কম্পিউটার বাধ্যতামূলক নয়।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[9px] font-bold font-sans">ব্যবহার</span>
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">মোবাইল</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 2 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 2 ? null : 2)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">অ্যাপটি কি অফলাইনে চলবে?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-bold font-sans">ব্যবহার</span>
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">অনলাইন</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 2" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> জি না, এটি একটি সম্পূর্ণ অনলাইন/ক্লাউড-ভিত্তিক সফটওয়্যার। আপনার ডেটা সুরক্ষিত রাখতে এবং রিয়েল-টাইমে হিসাব মিলাতে ইন্টারনেট সংযোগ প্রয়োজন।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[9px] font-bold font-sans">ব্যবহার</span>
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">অনলাইন</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 3 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 3 ? null : 3)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">ডেটা লস হবে না তো?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">সিকিউরিটি</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 3" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> না, ডেটা হারানোর কোনো সুযোগ নেই। আমরা উন্নত সিকিউরিটি ইনফ্রাস্ট্রাকচার ব্যবহার করি এবং আপনার ডেটা প্রতিদিন ক্লাউডে সুরক্ষিতভাবে অটো-ব্যাকআপ নিয়ে রাখা হয়।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">সিকিউরিটি</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 4 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 4 ? null : 4)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">হিসাবে ভুল করলে পরে ঠিক করা যাবে?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-bold font-sans">হিসাব</span>
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">পারমিশন</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 4" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> হ্যাঁ, এডমিন বা অনুমতিপ্রাপ্ত ব্যবহারকারীরা যেকোনো ভুল এন্ট্রি এডিট বা মুছে ফেলে পুনরায় নতুন সঠিক এন্ট্রি দিতে পারবেন।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[9px] font-bold font-sans">হিসাব</span>
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">পারমিশন</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 5 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 5 ? null : 5)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">আমি Edit/Update বা Delete করলে সেটার ইতিহাস দেখা যাবে?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-bold font-sans">হিস্ট্রি</span>
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">অডিট</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 5" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> হ্যাঁ, অ্যাক্টিভিটি হিস্ট্রি বা লগস পেজে যেকোনো সংশোধন, ডিলিট বা আপডেটের সম্পূর্ণ ইতিহাস ব্যবহারকারীর নাম ও তারিখসহ স্বয়ংক্রিয়ভাবে সংরক্ষিত থাকে।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[9px] font-bold font-sans">হিস্ট্রি</span>
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">অডিট</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-300"
                 :class="active === 6 ? 'border-primary shadow-sm ring-1 ring-primary/20' : 'border-gray-100 dark:border-slate-800 hover:border-gray-200 dark:hover:border-slate-700'">
                <button @click="active = (active === 6 ? null : 6)" type="button"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-primary/10 border border-primary/20 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-sm sm:text-base">আমার লাভ-লস কি ম্যানেজার দেখতে পারবে?</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-bold font-sans">পারমিশন</span>
                            <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold font-sans">রিপোর্ট</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-300"
                             :class="active === 6 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>
                <div x-show="active === 6" x-collapse>
                    <div class="px-5 pb-5 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                            <span class="text-primary font-bold">উত্তর:</span> রোল ও পারমিশন কন্ট্রোল প্যানেল থেকে আপনি চাইলে ম্যানেজার বা অন্য ব্যবহারকারীর জন্য লাভ-লস সংক্রান্ত রিপোর্ট দেখার অনুমতি বন্ধ অথবা চালু করতে পারবেন।
                        </p>
                        <div class="flex sm:hidden items-center gap-1.5 mt-3">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[9px] font-bold font-sans">পারমিশন</span>
                            <span class="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-[9px] font-bold font-sans">রিপোর্ট</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
