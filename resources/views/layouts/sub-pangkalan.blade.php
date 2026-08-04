<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sub Pangkalan - Sistem LPG')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        #sidebar nav::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        #sidebar nav {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2.5 rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-300 transform cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 w-72 bg-slate-900 text-white flex flex-col flex-shrink-0 z-40 border-r border-slate-800">
            <!-- Sidebar Header / Brand -->
            <div class="p-6 border-b border-slate-800/60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-full border border-slate-700/50 overflow-hidden flex items-center justify-center p-0.5 shrink-0 shadow-md">
                        @php
                            $customLoginLogo = \App\Models\Setting::getValue('login_logo');
                            $defaultLogoUrl = $customLoginLogo ? asset('storage/' . $customLoginLogo) : asset('images/elpiji_logo.png');
                            $userPhoto = auth()->user()->photo;
                            $logoUrl = $userPhoto ? asset('storage/' . $userPhoto) : $defaultLogoUrl;
                        @endphp
                        <img src="{{ $logoUrl }}" alt="Logo Pangkalan" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="overflow-hidden">
                        <h1 class="text-sm font-bold tracking-tight bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent truncate">
                            {{ auth()->user()->subPangkalan->name ?? 'Sub Pangkalan' }}
                        </h1>
                        <p class="text-[10px] text-blue-400 font-semibold tracking-wider uppercase">Sub Pangkalan (Pengecer)</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('sub-pangkalan.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Data Pelanggan -->
                <a href="{{ route('sub-pangkalan.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.customers.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Data Pelanggan</span>
                </a>

                <!-- Jual ke Pelanggan -->
                <a href="{{ route('sub-pangkalan.sell.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.sell.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Jual ke Pelanggan</span>
                </a>

                <!-- Tukar Tabung Kosong -->
                <a href="{{ route('sub-pangkalan.exchange.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.exchange.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span>Tukar Tabung Kosong</span>
                </a>

                <!-- Riwayat Transaksi -->
                <a href="{{ route('sub-pangkalan.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.history') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 112-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Riwayat Transaksi</span>
                </a>

                <!-- Profil Saya -->
                <a href="{{ route('sub-pangkalan.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition duration-200 {{ request()->routeIs('sub-pangkalan.profile') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profil Saya</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-800/60 bg-slate-950/40">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        @php
                            $spAvatar = auth()->user()->photo;
                        @endphp
                        @if($spAvatar)
                            <img src="{{ asset('storage/' . $spAvatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-200 shrink-0">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                        @endif
                        <span class="text-xs font-semibold text-slate-300 truncate" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-rose-500/10 transition cursor-pointer" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay -->
        <div id="overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-30 lg:hidden hidden"></div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header for Mobile -->
            <header class="h-16 bg-white border-b border-slate-100 lg:hidden flex items-center justify-end px-6 shrink-0"></header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-8 pt-6 lg:pt-8 bg-slate-50">
                <!-- Toast Notifications -->
                @if(session('success'))
                    <div class="max-w-md bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 shadow-xs flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-md bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl mb-6 shadow-xs flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');

        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            menuBtn.classList.toggle('translate-x-72');
            overlay.classList.toggle('hidden');
            
            // Toggle hamburger / close icon
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                menuBtn.innerHTML = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                `;
            } else {
                menuBtn.innerHTML = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                `;
            }
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            menuBtn.classList.remove('translate-x-72');
            overlay.classList.add('hidden');
            menuBtn.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            `;
        });
    </script>
    @stack('scripts')
</body>
</html>
