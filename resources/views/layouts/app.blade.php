<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ in_array(app()->getLocale(), ['ar', 'ur']) ? 'rtl' : 'ltr' }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AI Sales Call Analyzer — Every call transcribed, scored, and turned into revenue')</title>
    <meta name="description" content="@yield('meta_description', 'The premier sales-call intelligence platform. 100% automated QA scoring, live assist, objection detection, and CRM sync.')">
    <meta name="author" content="Imran Ahmed (https://imrandev.bd)">

    <!-- OpenGraph / Twitter Cards -->
    <meta property="og:title" content="@yield('title', 'AI Sales Call Analyzer')">
    <meta property="og:description" content="Every call transcribed, scored, and turned into revenue.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Noto Sans Bengali', 'Amiri', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 font-sans antialiased selection:bg-brand-500 selection:text-white transition-colors duration-200 min-h-screen flex flex-col justify-between">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-brand-600 to-indigo-800 text-white text-xs py-2 px-4 text-center font-medium">
        ⚡ <span>Introducing AI Call Intelligence 2.0 with Real-Time Objection Detection & Biometric Attendance Sync.</span>
        <a href="/live-demo" class="underline ml-2 font-bold hover:text-indigo-200">Try Live Demo &rarr;</a>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                </div>
                <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                    AI Sales Call Analyzer
                </span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600 dark:text-gray-300">
                <a href="/features" class="hover:text-brand-600 dark:hover:text-brand-400 transition">Features</a>
                <a href="/solutions/industry/saas" class="hover:text-brand-600 dark:hover:text-brand-400 transition">Solutions</a>
                <a href="/pricing" class="hover:text-brand-600 dark:hover:text-brand-400 transition">Pricing</a>
                <a href="/module-explorer" class="hover:text-brand-600 dark:hover:text-brand-400 transition">Build Your Plan</a>
                <a href="/integrations" class="hover:text-brand-600 dark:hover:text-brand-400 transition">Integrations</a>
                <a href="/live-demo" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Live Demo</a>
            </nav>

            <!-- Actions (Theme Toggle, Panels Login) -->
            <div class="flex items-center gap-3">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg border border-gray-200 dark:border-gray-800 transition">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <a href="/app/login" class="text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-brand-600 px-3 py-2">
                    Sign In
                </a>

                <a href="/live-demo" class="px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm transition">
                    Test 12 Roles &rarr;
                </a>
            </div>
        </div>
    </header>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 pt-16 pb-12 mt-20 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold">AI</div>
                        <span class="font-bold text-gray-900 dark:text-white">AI Sales Call Analyzer</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs leading-relaxed max-w-sm mb-4">
                        Every call transcribed, scored, and turned into revenue. Modular, multi-tenant intelligence platform for startups, call centers, and global enterprises.
                    </p>
                    <p class="text-xs text-gray-400">
                        Designed & Built by <strong>Imran Ahmed</strong> • <a href="https://imrandev.bd" target="_blank" class="text-brand-600 hover:underline">https://imrandev.bd</a>
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-xs uppercase tracking-wider mb-4">Product</h4>
                    <ul class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                        <li><a href="/features" class="hover:text-brand-600">All 22 Modules</a></li>
                        <li><a href="/module-explorer" class="hover:text-brand-600">Module Explorer</a></li>
                        <li><a href="/pricing" class="hover:text-brand-600">Plans & Bundles</a></li>
                        <li><a href="/integrations" class="hover:text-brand-600">Telephony & CRMs</a></li>
                        <li><a href="/live-demo" class="hover:text-brand-600">Live Demo Sandbox</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-xs uppercase tracking-wider mb-4">Solutions</h4>
                    <ul class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                        <li><a href="/solutions/industry/call_center" class="hover:text-brand-600">Call Centers & BPO</a></li>
                        <li><a href="/solutions/industry/saas" class="hover:text-brand-600">SaaS Sales Teams</a></li>
                        <li><a href="/solutions/industry/real_estate" class="hover:text-brand-600">Real Estate</a></li>
                        <li><a href="/solutions/industry/insurance" class="hover:text-brand-600">Insurance & Banking</a></li>
                        <li><a href="/compare" class="hover:text-brand-600">Competitor Comparison</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-xs uppercase tracking-wider mb-4">Legal & Trust</h4>
                    <ul class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                        <li><a href="/security" class="hover:text-brand-600">Security & GDPR</a></li>
                        <li><a href="/legal/terms" class="hover:text-brand-600">Terms of Service</a></li>
                        <li><a href="/legal/privacy" class="hover:text-brand-600">Privacy Policy</a></li>
                        <li><a href="/legal/dpa" class="hover:text-brand-600">DPA & HIPAA</a></li>
                        <li><a href="/legal/sla" class="hover:text-brand-600">99.99% SLA</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                <p>&copy; {{ date('Y') }} AI Sales Call Analyzer. All rights reserved. Developed by Imran Ahmed.</p>
                <div class="flex items-center gap-6">
                    <span>Performance Powered by <strong class="text-brand-500">Drutogoti</strong></span>
                    <span>Multilingual PDFs via <strong class="text-brand-500">Unicode PDF</strong></span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
