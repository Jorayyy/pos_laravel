<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VetPOS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50/80 min-h-screen antialiased">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: localStorage.getItem('sidebar-open') === 'true' }">
        <aside
            id="sidebar"
            class="w-64 bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 text-gray-300 flex-shrink-0 flex flex-col transition-all duration-300 max-lg:fixed max-lg:z-50 max-lg:h-full max-lg:-translate-x-full shadow-2xl shadow-gray-900/50"
            :class="{ 'max-lg:translate-x-0': sidebarOpen }"
            x-init="$nextTick(() => { const el = document.getElementById('sidebar-nav'); if (el) el.scrollTop = localStorage.getItem('sidebar-scroll') || 0 })"
        >
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-white font-bold text-lg tracking-tight">VetPOS</span>
                        <p class="text-[10px] text-gray-500 -mt-0.5 tracking-wide uppercase">Management System</p>
                    </div>
                </a>
            </div>

            <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-3 px-3 text-sm scroll-smooth" onscroll="localStorage.setItem('sidebar-scroll', this.scrollTop)">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('pos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('pos') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span class="font-semibold {{ request()->routeIs('pos') ? '' : 'text-emerald-400' }}">Point of Sale</span>
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">Records</div>
                <a href="{{ route('admin.owners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.owners.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pet Owners
                </a>
                <a href="{{ route('admin.pets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.pets.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                    Pets
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">Inventory</div>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Products
                </a>
                <a href="{{ route('admin.product-categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.product-categories.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    Categories
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.inventory.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Inventory
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">Services</div>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.services.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Vet Services
                </a>
                <a href="{{ route('admin.service-categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.service-categories.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    Service Categories
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">Sales</div>
                <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.sales.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Sales
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">Reports</div>
                <a href="{{ route('admin.reports.daily-sales') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Reports
                </a>

                <div class="px-3 py-2 text-[10px] text-gray-600 uppercase tracking-widest font-semibold mt-5 mb-1">System</div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm' : 'hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Audit Logs
                </a>

                <div class="h-4"></div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white/80 backdrop-blur-xl border-b border-gray-200/60 px-6 py-3 flex items-center justify-between flex-shrink-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebar-open', sidebarOpen)" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    @yield('header')
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 text-right">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-400 leading-tight">{{ ucfirst(auth()->user()->role?->slug ?? 'user') }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-emerald-500/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                        @foreach($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('main');

            if (window.innerWidth >= 1024) {
                sidebarOpen = localStorage.getItem('sidebar-open') !== 'false';
            }
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
