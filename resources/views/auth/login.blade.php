<!DOCTYPE html>
<html lang="bn" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>লগইন - Bricks Land Management Dashboard</title>
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

    <!-- Login Container -->
    <div class="w-full max-w-4xl z-10 flex flex-col items-center">

        <!-- Main Login Card -->
        <div class="w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 max-w-3xl border border-gray-100 dark:border-slate-800 transition-all duration-300">
            
            <!-- Left Side: Geometric Vector Illustration (5 columns) -->
            <div class="hidden md:flex md:col-span-5 bg-primary p-8 flex-col items-center justify-center relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-700/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl"></div>

                <!-- Geometric 3D-like Composition built with Tailwind & SVGs -->
                <div class="relative w-full aspect-square max-w-[220px] bg-gradient-to-tr from-primary-dark to-primary-light rounded-3xl p-6 shadow-xl flex items-center justify-center overflow-hidden border border-emerald-500/20">
                    
                    <!-- SVG Geometric Art matching image_67b022.jpg -->
                    <svg class="w-full h-full" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Emerald Cylinder Base/Circle -->
                        <circle cx="100" cy="120" r="45" fill="url(#circleGrad)" filter="url(#dropShadow)"/>
                        
                        <!-- Coral/Pink Arch on the Right -->
                        <path d="M125 150V110C125 93.4315 138.431 80 155 80C171.569 80 185 93.4315 185 110V150H125Z" fill="url(#pinkGrad)" opacity="0.9"/>
                        
                        <!-- Mint/Green Arch on the Left -->
                        <path d="M15 150V110C15 90.67 30.67 75 50 75C69.33 75 85 90.67 85 110V150H15Z" fill="url(#mintGrad)" opacity="0.95"/>
                        
                        <!-- Coral Pillar Column in the front center -->
                        <rect x="75" y="95" width="20" height="55" rx="10" fill="url(#pinkPillar)" />
                        
                        <!-- Emerald sphere hovering on the top left -->
                        <circle cx="75" cy="50" r="18" fill="url(#sphereGrad)" filter="url(#sphereShadow)"/>
                        
                        <!-- Gradients definition -->
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

            <!-- Right Side: Login Form (7 columns) -->
            <div class="col-span-1 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white dark:bg-slate-900 transition-colors duration-300">
                
                <!-- Welcome Header -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#034C3C] dark:text-emerald-400 mb-2 font-sans tracking-wide">
                        আপনাকে স্বাগতম !
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-light">
                        আপনার ব্যবসা পরিচালনার জন্য লগইন করুন
                    </p>
                </div>

                <!-- Form with Alpine.js Validation & Interactions -->
                <form 
                    action="{{ route('login') }}" 
                    method="POST" 
                    x-data="{ 
                        email: '', 
                        password: '',
                        showPassword: false,
                        submitted: false,
                        emailErrorMsg: '{{ $errors->first('email') }}',
                        passwordErrorMsg: '{{ $errors->first('password') }}',
                        get emailError() {
                            if (this.emailErrorMsg) return this.emailErrorMsg;
                            if (!this.submitted) return '';
                            return this.email.trim() === '' ? 'ইমেইল আবশ্যক' : '';
                        },
                        get passwordError() {
                            if (this.passwordErrorMsg) return this.passwordErrorMsg;
                            if (!this.submitted) return '';
                            return this.password.length < 4 ? 'পাসওয়ার্ড কমপক্ষে ৪ অক্ষরের হতে হবে' : '';
                        },
                        submitForm(e) {
                            this.submitted = true;
                            if (this.emailError || this.passwordError) {
                                e.preventDefault();
                            }
                        }
                    }"
                    x-init="let count = 0; let interval = setInterval(() => { 
                        let eField = document.getElementById('email');
                        let pField = document.getElementById('password');
                        if (eField) { eField.value = ''; }
                        if (pField) { pField.value = ''; }
                        email = ''; 
                        password = ''; 
                        count++; 
                        if (count > 10) clearInterval(interval); 
                    }, 50)"
                    @submit="submitForm($event)">
                    
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                            ইমেইল
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            x-model="email"
                            @input="emailErrorMsg = ''; submitted = false"
                            autocomplete="username"
                            :class="emailError ? 'w-full py-2.5 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm border-red-500 ring-4 ring-red-100 dark:ring-red-950/20' : 'w-full py-2.5 px-4 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10'"
                            placeholder="আপনার ইমেইল লিখুন">
                        
                        <!-- Client-Side or Server-Side Error Message -->
                        <p x-show="emailError" x-text="emailError" class="text-red-500 text-[10px] mt-1.5 font-semibold" x-cloak></p>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-8">
                        <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                            পাসওয়ার্ড
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                id="password"
                                x-model="password"
                                @input="passwordErrorMsg = ''; submitted = false"
                                autocomplete="current-password"
                                :class="(passwordError || emailErrorMsg) ? 'w-full py-2.5 pl-4 pr-12 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm border-red-500 ring-4 ring-red-100 dark:ring-red-950/20' : 'w-full py-2.5 pl-4 pr-12 rounded-xl border bg-gray-50 dark:bg-slate-800 dark:text-white transition-all duration-200 outline-none text-sm border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10'"
                                placeholder="আপনার পাসওয়ার্ড লিখুন">
                            
                            <!-- Toggle Button -->
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary focus:outline-none transition-colors">
                                
                                <!-- Eye Open Icon (shown in text mode) -->
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                
                                <!-- Eye Closed Icon (shown in password mode) -->
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 013.859-4.877m2.138-2.138A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M12 9a3 3 0 00-3 3m3 0a3 3 0 003-3M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Client-Side or Server-Side Error Message -->
                        <p x-show="passwordError" x-text="passwordError" class="text-red-500 text-[10px] mt-1.5 font-semibold" x-cloak></p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#009E74] focus:ring-[#009E74] dark:bg-slate-800 dark:border-slate-700" name="remember">
                            <span class="ms-2 text-xs text-gray-600 dark:text-gray-400 font-medium">আমায় মনে রাখুন</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-[#009E74] hover:underline font-semibold" href="{{ route('password.request') }}">
                                পাসওয়ার্ড ভুলে গেছেন?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-[#009E74] hover:bg-[#008762] text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 active:scale-[0.98] transition-all duration-150 text-sm mb-4">
                        লগইন
                    </button>

                </form>
            </div>
        </div>
    </div>
</body>
</html>
