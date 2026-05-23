<nav x-data="{ open: false, userOpen: false }" class="hris-nav">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <div class="hris-nav-inner">

        <!-- ── Left: Logo + Links ── -->
        <div style="display:flex; align-items:center;">

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="hris-logo">
                <div class="hris-logo-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="hris-logo-wordmark"><em>Payroll</em></span>
            </a>

            <!-- Desktop nav links -->
            <div class="hris-nav-links">
                <a href="{{ route('dashboard') }}"
                    class="hris-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{ __('Dashboard') }}
                </a>

                @can('admin')
                    <a href="{{ route('karyawan.index') }}"
                        class="hris-nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                        {{ __('Data Karyawan') }}
                    </a>

                    <a href="{{ route('absensi.index') }}"
                        class="hris-nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                        {{ __('Data Absensi') }}
                    </a>

                    <a href="{{ route('penggajian.index') }}"
                        class="hris-nav-link {{ request()->routeIs('penggajian.*') ? 'active' : '' }}">
                        {{ __('Kalkulasi Penggajian') }}
                    </a>
                @endcan
            </div>
        </div>

        <!-- ── Right: Role pill + User dropdown ── -->
        <div class="hris-nav-right">

            @can('admin')
                <span class="hris-role-pill">Admin</span>
            @endcan

            <!-- Desktop user dropdown -->
            <div style="position:relative;" class="hris-chevron-wrap" :aria-expanded="userOpen.toString()">
                <button @click="userOpen = !userOpen" @click.outside="userOpen = false" class="hris-user-btn">
                    <div class="hris-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="hris-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown panel -->
                <div x-show="userOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    style="
                        position:absolute; top:calc(100% + 8px); right:0;
                        min-width:190px;
                        background:#12151F;
                        border:1px solid var(--border);
                        border-radius:10px;
                        padding:6px;
                        z-index:50;
                    ">

                    <a href="{{ route('profile.edit') }}"
                        style="
                        display:flex; align-items:center; gap:8px;
                        padding:9px 12px; font-size:12px;
                        color:var(--text-muted); border-radius:7px;
                        text-decoration:none;
                        transition:background .15s, color .15s;
                        font-family:'DM Sans',sans-serif;
                    "
                        onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='var(--text-primary)'"
                        onmouseout="this.style.background='';this.style.color='var(--text-muted)'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Profile') }}
                    </a>

                    <div style="height:1px; background:var(--border); margin:4px 0;"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            style="
                            display:flex; align-items:center; gap:8px;
                            padding:9px 12px; font-size:12px;
                            color:rgba(240,100,100,0.75); border-radius:7px;
                            width:100%; background:none; border:none;
                            cursor:pointer; text-align:left;
                            transition:background .15s, color .15s;
                            font-family:'DM Sans',sans-serif;
                        "
                            onmouseover="this.style.background='rgba(240,100,100,0.07)';this.style.color='rgba(240,120,120,1)'"
                            onmouseout="this.style.background='';this.style.color='rgba(240,100,100,0.75)'">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Hamburger (mobile) -->
            <button @click="open = !open" class="hris-mobile-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!open">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="open">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    <!-- ── Mobile menu ── -->
    <div :class="{ 'open': open }" class="hris-mobile-menu">

        <a href="{{ route('dashboard') }}"
            class="hris-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            {{ __('Dashboard') }}
        </a>

        @can('admin')
            <a href="{{ route('karyawan.index') }}"
                class="hris-mobile-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                {{ __('Data Karyawan') }}
            </a>

            <a href="{{ route('absensi.index') }}"
                class="hris-mobile-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                {{ __('Data Absensi') }}
            </a>

            <a href="{{ route('penggajian.index') }}"
                class="hris-mobile-link {{ request()->routeIs('penggajian.*') ? 'active' : '' }}">
                {{ __('Kalkulasi Penggajian') }}
            </a>
        @endcan

        <!-- User info -->
        <div class="hris-mobile-user">
            <div class="hris-mobile-uname">{{ Auth::user()->name }}</div>
            <div class="hris-mobile-email">{{ Auth::user()->email }}</div>
        </div>

        <a href="{{ route('profile.edit') }}" class="hris-mobile-link">
            {{ __('Profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="hris-mobile-link hris-mobile-link-danger"
                style="width:100%; background:none; border:none; cursor:pointer; text-align:left; font-family:'DM Sans',sans-serif;">
                {{ __('Log Out') }}
            </button>
        </form>

    </div>

</nav>
