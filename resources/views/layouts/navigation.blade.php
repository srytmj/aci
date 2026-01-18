@php
    $isSuperAdmin = Auth::user()->id_level == 1;
    $activeRoleId = session('active_role_id');

    $semuaAksesTersedia = DB::table('akses')->get();

    $userAksesData = DB::table('user_akses')
        ->join('akses', 'user_akses.id_akses', '=', 'akses.id_akses')
        ->where('user_id', Auth::id())
        ->select('akses.id_akses', 'akses.nama_akses', 'akses.fitur_slug')
        ->get();

    // FIX LOGIC: Cari role aktif
    $activeRole = null;
    if ($activeRoleId) {
        $activeRole = DB::table('akses')->where('id_akses', $activeRoleId)->first();
    }

    // Fallback jika session kosong ATAU role yang di session tiba-tiba tidak ditemukan
    if (!$activeRole) {
        if ($isSuperAdmin) {
            $activeRole = (object) ['id_akses' => null, 'nama_akses' => 'Administrator', 'fitur_slug' => 'all'];
        } else {
            $activeRole =
                $userAksesData->first() ?:
                (object) ['id_akses' => null, 'nama_akses' => 'No Access', 'fitur_slug' => ''];
        }
    }

    $slugs = explode(',', $activeRole->fitur_slug ?? '');

    $hasAkses = function ($targetMenu) use ($slugs) {
        return in_array('all', $slugs) || in_array($targetMenu, $slugs);
    };

    $displayAkses = $isSuperAdmin ? $semuaAksesTersedia : $userAksesData;
@endphp

{{-- Mulai HTML Sidebar lo di bawah sini --}}

<nav x-data="{
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

        @if ($hasAkses('all') || $hasAkses('proyek') || $hasAkses('vendor') || $hasAkses('coa'))
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

                    @if ($hasAkses('all'))
                        <div x-data="{ userSubOpen: {{ request()->is('master/users*') || request()->is('master/akses*') ? 'true' : 'false' }} }">
                            <button @click="userSubOpen = !userSubOpen"
                                class="w-full flex items-center justify-between py-2 pl-12 pr-4 text-sm {{ request()->is('master/users*') || request()->is('master/akses*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
                                <span>User Management</span>
                                <svg :class="userSubOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="userSubOpen" x-collapse
                                class="pl-4 border-l border-gray-100 ml-14 space-y-1 my-1">
                                <a href="{{ route('akses.index') }}"
                                    class="block py-1.5 text-xs {{ request()->routeIs('akses.*') ? 'text-indigo-600 font-medium' : 'text-gray-400 hover:text-indigo-600' }}">Akses
                                    User</a>
                                <a href="{{ route('users.index') }}"
                                    class="block py-1.5 text-xs {{ request()->routeIs('users.*') ? 'text-indigo-600 font-medium' : 'text-gray-400 hover:text-indigo-600' }}">Data
                                    User</a>
                            </div>
                        </div>
                    @endif

                    @if ($hasAkses('proyek') || $hasAkses('termin'))
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
                            <div x-show="subOpen" x-collapse class="pl-4 border-l border-gray-100 ml-14 space-y-1 my-1">
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
                    @endif

                    @if ($hasAkses('vendor'))
                        <a href="{{ route('vendor.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('vendor.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Vendor</a>
                    @endif

                    @if ($hasAkses('coa'))
                        <a href="{{ route('coa.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('coa.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">COA
                            (Akun)</a>
                    @endif

                    @if ($hasAkses('all'))
                        <a href="{{ route('kategori.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kategori.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kategori
                            Kas</a>
                    @endif

                    @if ($hasAkses('all') || $hasAkses('coa'))
                        <a href="{{ route('lra.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('lra.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">LRA
                            (Laporan Realisasi Anggaran)</a>
                    @endif
                </div>
            </div>
        @endif

        @if ($hasAkses('all') || $hasAkses('kas_masuk') || $hasAkses('kas_keluar'))
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <div x-show="openMenu === 'transaksi'" x-collapse x-cloak class="mt-1 space-y-1">

                    @if ($hasAkses('kas_masuk'))
                        <a href="{{ route('kas-masuk.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kas-masuk.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kas
                            Masuk</a>
                    @endif

                    @if ($hasAkses('kas_keluar'))
                        <a href="{{ route('kas-keluar.index') }}"
                            class="flex items-center py-2.5 pl-12 pr-4 text-sm {{ request()->routeIs('kas-keluar.*') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">Kas
                            Keluar</a>
                    @endif
                </div>
            </div>
        @endif

        <div class="pt-4 pb-2">
            <p class="text-[10px] font-bold text-gray-400 uppercase px-4 tracking-widest">Laporan</p>
        </div>

        @if ($hasAkses('all') || $hasAkses('coa'))
            {{-- Jurnal Umum --}}
            <a href="{{ route('jurnal.index') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('jurnal.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Jurnal Umum
            </a>

            {{-- Laporan Realisasi Anggaran --}}
            <a href="{{ route('lra.laporan') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('lra.laporan')) ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                LRA Proyek
            </a>

            {{-- Laporan Laba Rugi --}}
            <a href="{{ route('lra.labarugi') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('lra.labarugi')) ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Laba Rugi Proyek
            </a>
        @endif
    </div>

    {{-- Footer Section: Theme & Profile --}}
    <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 shrink-0">
        {{-- Theme Switcher --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
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

        {{-- Profile Dropdown --}}
        <div class="p-4" x-data="{ profileOpen: false }">
            <div class="relative">
                <button @click="profileOpen = !profileOpen"
                    class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-all duration-200">

                    {{-- Avatar --}}
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>

                    {{-- Label Nama & Role --}}
                    <div class="flex-1 text-left overflow-hidden">
                        <p class="text-[13px] font-bold text-gray-800 dark:text-white truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[10px] text-indigo-500 dark:text-indigo-400 font-bold truncate uppercase">
                            {{ $activeRole->nama_akses ?? 'Staff' }}
                        </p>
                    </div>

                    {{-- Arrow Icon --}}
                    <svg :class="profileOpen ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7">
                        </path>
                    </svg>
                </button>

                {{-- Dropdown Content --}}
                <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.away="profileOpen = false"
                    class="absolute bottom-full left-0 w-full mb-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">

                    <div class="p-2 space-y-1">
                        <div class="px-3 py-2 border-b border-gray-50 dark:border-gray-700 mb-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Otoritas Aktif</p>
                        </div>

                        {{-- List Semua Role yang Dimiliki --}}
                        <div class="space-y-1">
                            @php
                                $listAkses = $isSuperAdmin ? $semuaAksesTersedia : $userAksesData;
                            @endphp

                            @foreach ($displayAkses as $akses)
                                <form action="{{ route('switch.role') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_akses" value="{{ $akses->id_akses }}">
                                    <button type="submit"
                                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition-all mb-1
            {{ $activeRole->id_akses == $akses->id_akses ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-50 hover:bg-indigo-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300' }}">

                                        <span class="text-xs font-semibold">{{ $akses->nama_akses }}</span>

                                        @if ($activeRole->id_akses == $akses->id_akses)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-50 dark:border-gray-700 my-1"></div>

                        {{-- Tombol Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
