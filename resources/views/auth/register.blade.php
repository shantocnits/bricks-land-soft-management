<!DOCTYPE html>
<html lang="bn" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>নিবন্ধন - Bricks Land Management Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js CDN for standalone interactive features -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Blurry Brick Field Background Layer -->
    <div class="absolute inset-0 bg-cover bg-center z-0 opacity-40 blur-sm scale-105" 
         style="background-image: url('https://images.unsplash.com/photo-1590069261209-f8e9b8642343?auto=format&fit=crop&w=1200&q=80');">
    </div>
    
    <!-- Gradient overlay to match the foggy/dusty brick factory mood -->
    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-950/80 via-slate-900/90 to-emerald-900/50 z-0"></div>

    <!-- Register Container -->
    <div class="w-full max-w-4xl z-10 flex flex-col items-center">

        <!-- Main Register Card -->
        <div class="w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 max-w-3xl border border-gray-100 dark:border-slate-800 transition-all duration-300">
            
            <!-- Left Side: Geometric Vector Art (5 columns) -->
            <div class="hidden md:flex md:col-span-5 bg-primary p-8 flex-col items-center justify-center relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-700/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl"></div>

                <!-- Geometric 3D-like Composition -->
                <div class="relative w-full aspect-square max-w-[220px] bg-gradient-to-tr from-primary-dark to-primary-light rounded-3xl p-6 shadow-xl flex items-center justify-center overflow-hidden border border-emerald-500/20">
                    <svg class="w-full h-full" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="100" cy="120" r="45" fill="url(#circleGrad)" filter="url(#dropShadow)"/>
                        <path d="M125 150V110C125 93.4315 138.431 80 155 80C171.569 80 185 93.4315 185 110V150H125Z" fill="url(#pinkGrad)" opacity="0.9"/>
                        <path d="M15 150V110C15 90.67 30.67 75 50 75C69.33 75 85 90.67 85 110V150H15Z" fill="url(#mintGrad)" opacity="0.95"/>
                        <rect x="75" y="95" width="20" height="55" rx="10" fill="url(#pinkPillar)" />
                        <circle cx="75" cy="50" r="18" fill="url(#sphereGrad)" filter="url(#sphereShadow)"/>
                        <defs>
                            <linearGradient id="circleGrad" x1="100" y1="75" x2="100" y2="165" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#09AA84"/>
                                <stop offset="100%" stop-color="#023E31"/>
                            </linearGradient>
                            <linearGradient id="pinkGrad" x1="155" y1="80" x2="155" y2="150" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#FFA8A8"/>
                                <stop offset="100%" stop-color="#F06595"/>
                            </linearGradient>
                            <linearGradient id="mintGrad" x1="50" y1="75" x2="50" y2="150" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#69DB7C"/>
                                <stop offset="100%" stop-color="#12B886"/>
                            </linearGradient>
                            <linearGradient id="pinkPillar" x1="85" y1="95" x2="85" y2="150" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#FFC9C9"/>
                                <stop offset="100%" stop-color="#FF8787"/>
                            </linearGradient>
                            <linearGradient id="sphereGrad" x1="75" y1="32" x2="75" y2="68" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#12B886"/>
                                <stop offset="100%" stop-color="#087F5B"/>
                            </linearGradient>
                            <filter id="dropShadow" x="45" y="70" width="110" height="110" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="#012019" flood-opacity="0.5"/>
                            </filter>
                            <filter id="sphereShadow" x="52" y="29" width="46" height="46" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feDropShadow dx="0" dy="5" stdDeviation="3" flood-color="#012019" flood-opacity="0.4"/>
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>

            <!-- Right Side: Register Form (7 columns) -->
            <div class="col-span-1 md:col-span-7 p-8 sm:p-10 flex flex-col justify-center bg-white dark:bg-slate-900 transition-colors duration-300">
                
                <!-- Welcome Header -->
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#034C3C] dark:text-emerald-400 mb-1 font-sans tracking-wide">
                        নতুন অ্যাকাউন্ট !
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-light">
                        আপনার তথ্য দিয়ে ড্যাশবোর্ডে নিবন্ধন সম্পন্ন করুন
                    </p>
                </div>

                <!-- Registration Form -->
                <form 
                    action="{{ route('register') }}" 
                    method="POST" 
                    x-data="{ 
                        name: '',
                        email: '', 
                        password: '',
                        password_confirmation: '',
                        submitted: false,
                        nameErrorMsg: '',
                        emailErrorMsg: '',
                        passwordErrorMsg: '',
                        passwordConfErrorMsg: '',
                        get nameError() {
                            if (this.nameErrorMsg) return this.nameErrorMsg;
                            if (!this.submitted) return '';
                            return this.name.trim() === '' ? 'ইউজারনেম আবশ্যক' : '';
                        },
                        get emailError() {
                            if (this.emailErrorMsg) return this.emailErrorMsg;
                            if (!this.submitted) return '';
                            if (this.email.trim() === '') return 'ইমেইল আবশ্যক';
                            return !this.email.includes('@') ? 'সঠিক ইমেইল দিন' : '';
                        },
                        get passwordError() {
                            if (this.passwordErrorMsg) return this.passwordErrorMsg;
                            if (!this.submitted) return '';
                            return this.password.length < 8 ? 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে' : '';
                        },
                        get passwordConfError() {
                            if (this.passwordConfErrorMsg) return this.passwordConfErrorMsg;
                            if (!this.submitted) return '';
                            return this.password !== this.password_confirmation ? 'পাসওয়ার্ড নিশ্চিতকরণ মিলছে না' : '';
                        },
                        submitForm(e) {
                            this.submitted = true;
                            this.nameErrorMsg = '';
                            this.emailErrorMsg = '';
                            this.passwordErrorMsg = '';
                            this.passwordConfErrorMsg = '';
                            if (this.name.trim() === '' || this.email.trim() === '' || !this.email.includes('@') || this.password.length < 8 || this.password !== this.password_confirmation) {
                                e.preventDefault();
                            }
                        }
                    }"
                    @submit="submitForm($event)">
                    
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            ইউজারনেম (Name)
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            x-model="name"
                            @input="nameErrorMsg = ''"
                            autocomplete="name"
                            class="w-full py-2 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm @error('name') border-red-500 ring-4 ring-red-100 dark:ring-red-950/20 @else border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 @enderror"
                            placeholder="আপনার ইউজারনেম লিখুন">
                        
                        <p x-show="nameError" x-text="nameError" class="text-red-500 text-[10px] mt-1 font-semibold" x-cloak></p>
                        @error('name')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            ইমেইল (Email)
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            x-model="email"
                            @input="emailErrorMsg = ''"
                            autocomplete="email"
                            class="w-full py-2 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm @error('email') border-red-500 ring-4 ring-red-100 dark:ring-red-950/20 @else border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 @enderror"
                            placeholder="আপনার ইমেইল লিখুন">
                        
                        <p x-show="emailError" x-text="emailError" class="text-red-500 text-[10px] mt-1 font-semibold" x-cloak></p>
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            পাসওয়ার্ড
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            x-model="password"
                            @input="passwordErrorMsg = ''"
                            autocomplete="new-password"
                            class="w-full py-2 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm @error('password') border-red-500 ring-4 ring-red-100 dark:ring-red-950/20 @else border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 @enderror"
                            placeholder="পাসওয়ার্ড লিখুন (কমপক্ষে ৮ অক্ষর)">
                        
                        <p x-show="passwordError" x-text="passwordError" class="text-red-500 text-[10px] mt-1 font-semibold" x-cloak></p>
                        @error('password')
                            <p class="text-red-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            পাসওয়ার্ড নিশ্চিত করুন
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            x-model="password_confirmation"
                            @input="passwordConfErrorMsg = ''"
                            autocomplete="new-password"
                            class="w-full py-2 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10"
                            placeholder="পুনরায় পাসওয়ার্ডটি লিখুন">
                        
                        <p x-show="passwordConfError" x-text="passwordConfError" class="text-red-500 text-[10px] mt-1 font-semibold" x-cloak></p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-[#009E74] hover:bg-[#008762] text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 active:scale-[0.98] transition-all duration-150 text-sm mb-4">
                        নিবন্ধন করুন
                    </button>

                    <!-- Link to Login -->
                    <div class="text-center">
                        <span class="text-xs text-gray-500">ইতিমধ্যে অ্যাকাউন্ট আছে?</span>
                        <a href="{{ route('login') }}" class="text-xs text-[#009E74] hover:underline font-extrabold ms-1">লগইন করুন</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
