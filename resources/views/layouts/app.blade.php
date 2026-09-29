<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laundry') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

<div class="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        {{-- TOMBOL TOGGLE SIDEBAR --}}
        <button type="button" class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <div class="sidebar-logo">
            <img src="{{ asset('image/laundry-login.png') }}" alt="Laundry">
        </div>

        <nav class="sidebar-menu">

            {{-- DASHBOARD --}}
            <a href="{{ route('home') }}"
                class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>


            {{-- PEMESANAN --}}
            <a href="{{ route('pemesanan.index') }}"
                class="sidebar-item {{ request()->routeIs('pemesanan.*') ? 'active' : '' }}">
                <i class="bi bi-basket-fill"></i>
                <span>Pemesanan</span>
            </a>


            {{-- PELANGGAN --}}
            <a href="{{ route('pelanggan.index') }}"
                class="sidebar-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Pelanggan</span>
            </a>


            {{-- LAYANAN --}}
            <a href="{{ route('layanan.index') }}"
                class="sidebar-item {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                <i class="bi bi-droplet-fill"></i>
                <span>Layanan</span>
            </a>


            {{-- TRANSAKSI --}}
            <a href="{{ route('pembayaran.index') }}"
                class="sidebar-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Transaksi</span>
            </a>


            {{-- REWARD --}}
            <a href="{{ route('reward.index') }}"
                class="sidebar-item {{ request()->routeIs('reward.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Reward Stempel</span>
            </a>


            {{-- LAPORAN --}}
            <a href="#" class="sidebar-item">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>


            {{-- PENGATURAN --}}
            <a href="#" class="sidebar-item">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan</span>
            </a>


            {{-- LOGOUT SIDEBAR --}}
            <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                @csrf

                <button type="submit" class="sidebar-item sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        </nav>
    </aside>


    {{-- BAGIAN KANAN --}}
    <div class="dashboard-main">

        {{-- TOPBAR --}}
        <header class="dashboard-topbar">

            {{-- TOMBOL SIDEBAR --}}
            <button type="button" class="mobile-sidebar-toggle" id="mobileSidebarToggle">
                <i class="bi bi-list"></i>
            </button>


            {{-- PROFILE USER --}}
            <div class="topbar-user-wrapper">

                <button type="button" class="topbar-user" id="profileButton">

                    <i class="bi bi-person-fill"></i>

                    <span>
                        {{ Auth::check() ? Auth::user()->name : 'Bhanu' }}
                    </span>

                    <i class="bi bi-chevron-down profile-arrow"></i>

                </button>


                {{-- PROFILE POPUP --}}
                <div class="profile-popup" id="profilePopup">

                    <div class="profile-popup-header">

                        <div class="profile-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div class="profile-name">
                            {{ Auth::check() ? Auth::user()->name : 'Bhanu' }}
                        </div>

                        <div class="profile-email">
                            {{ Auth::check() ? Auth::user()->email : '-' }}
                        </div>

                    </div>


                    <div class="profile-popup-menu">

                        <button type="button" class="profile-menu-item">
                            <i class="bi bi-person"></i>
                            <span>Profil Saya</span>
                        </button>


                        <form action="{{ route('logout') }}" method="POST">

                            @csrf

                            <button type="submit"
                                    class="profile-menu-item logout-item">

                                <i class="bi bi-box-arrow-right"></i>

                                <span>Logout</span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <main class="dashboard-content">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>