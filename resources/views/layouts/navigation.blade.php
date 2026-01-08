<nav x-data="{
    {{-- Deteksi menu yang aktif berdasarkan URL --}}
    openMenu: '{{ request()->is('master*') ? 'master' : (request()->is('transaksi*') ? 'transaksi' : '') }}',
        toggle(menu) {
            this.openMenu = this.openMenu === menu ? '' : menu
        }
}"
    class="h-full flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300">

    <div class="p-6 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                class="w-10 h-10 object-contain group-hover:scale-110 transition-transform duration-300">
            <div class="flex flex-col">
                <span class="font-bold text-sm tracking-widest dark:text-white uppercase">Konstruksi</span>
                <span class="text-[10px] text-gray-400 font-medium -mt-1 tracking-[0.2em]">MANAGEMENT</span>
            </div>
        </a>
    </div>

    <div class="flex-1 px-3 space-y-1 overflow-y-auto custom-scrollbar">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 
           {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Dashboard
        </a>

        <div class="pt-4 pb-2">
            <p class="text-[10px] font-bold text-gray-400 uppercase px-4 tracking-widest">Main Menu</p>
        </div>

        @if (Auth::user()->id_level == 1 || in_array(Auth::user()->id_jabatan, [2, 3]))
            <div class="space-y-1">
                <button @click="toggle('master')"
                    :class="openMenu === 'master' ? 'bg-gray-50 dark:bg-gray-700/50 text-indigo-600' :
                        'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                            </path>
                        </svg>
                        Master Data
                    </div>
                    <svg :class="openMenu === 'master' ? 'rotate-180' : ''"
                        class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openMenu === 'master'" x-collapse x-cloak class="mt-1 space-y-1">
                    @if (Auth::user()->id_level == 1)
                        <a href="{{ route('users.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('users.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Data
                            User</a>
                    @endif

                    @if (Auth::user()->id_level == 1 || Auth::user()->id_jabatan == 2)
                        <div x-data="{ subOpen: {{ request()->is('master/proyek*') || request()->is('master/termin*') || request()->is('master/pemberi*') ? 'true' : 'false' }} }">
                            <button @click="subOpen = !subOpen"
                                class="w-full flex items-center justify-between py-2 pl-12 pr-4 text-sm text-gray-500 hover:text-indigo-600">
                                <span>Proyek</span>
                                <svg :class="subOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="subOpen" x-collapse
                                class="pl-4 border-l border-gray-100 dark:border-gray-700 ml-14 space-y-1 my-1">
                                <a href="{{ route('pemberi.index') }}"
                                    class="block py-1.5 text-xs {{ request()->routeIs('pemberi.*') ? 'text-indigo-600 font-medium' : 'text-gray-400 hover:text-indigo-600' }}">Pemberi
                                    Proyek</a>
                                <a href="{{ route('proyek.index') }}"
                                    class="block py-1.5 text-xs {{ request()->routeIs('proyek.*') ? 'text-indigo-600 font-medium' : 'text-gray-400 hover:text-indigo-600' }}">Data
                                    Proyek</a>
                                <a href="{{ route('termin.index') }}"
                                    class="block py-1.5 text-xs {{ request()->routeIs('termin.*') ? 'text-indigo-600 font-medium' : 'text-gray-400 hover:text-indigo-600' }}">Termin
                                    Proyek</a>
                            </div>
                        </div>
                        <a href="{{ route('vendor.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('vendor.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Vendor</a>
                    @endif

                    @if (Auth::user()->id_level == 1 || Auth::user()->id_jabatan == 3)
                        <a href="{{ route('coa.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('coa.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">COA
                            (Akun)</a>
                    @endif
                </div>
            </div>
        @endif

        @if (Auth::user()->id_level == 1 || Auth::user()->id_jabatan == 3)
            <div class="space-y-1">
                <button @click="toggle('transaksi')"
                    :class="openMenu === 'transaksi' ? 'bg-gray-50 dark:bg-gray-700/50 text-indigo-600' :
                        'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200">
                    <div class="flex items-center font-semibold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Transaksi
                    </div>
                    <svg :class="openMenu === 'transaksi' ? 'rotate-180' : ''"
                        class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openMenu === 'transaksi'" x-collapse x-cloak class="mt-1 space-y-1">
                    <a href="{{ route('kategori.index') }}"
                        class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kategori.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kategori
                        Kas</a>
                    <a href="{{ route('kas-masuk.index') }}"
                        class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kas-masuk.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kas
                        Masuk</a>
                    <a href="{{ route('kas-keluar.index') }}"
                        class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kas-keluar.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kas
                        Keluar</a>
                </div>
            </div>

            <a href="{{ route('jurnal.index') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('jurnal.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                Jurnal Umum
            </a>
        @endif
    </div>


    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 shrink-0">
        <div class="flex items-center justify-between mb-4">
            <div
                class="flex gap-2 p-1 bg-white dark:bg-gray-800 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm">
                <button onclick="setTheme('#4f46e5')"
                    class="w-4 h-4 rounded-full bg-indigo-600 hover:scale-125 transition"></button>
                <button onclick="setTheme('#059669')"
                    class="w-4 h-4 rounded-full bg-emerald-600 hover:scale-125 transition"></button>
                <button onclick="setTheme('#e11d48')"
                    class="w-4 h-4 rounded-full bg-rose-600 hover:scale-125 transition"></button>
            </div>
            <button onclick="toggleDarkMode()"
                class="p-2 rounded-xl text-gray-400 dark:text-yellow-400 hover:bg-white dark:hover:bg-gray-800 transition-all">
                <svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-[13px] font-bold text-gray-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 truncate tracking-tight uppercase font-medium">
                    {{ Auth::user()->roles && Auth::user()->roles->count() > 0 ? Auth::user()->roles->first()->name : 'Staff' }}
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 text-gray-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>
