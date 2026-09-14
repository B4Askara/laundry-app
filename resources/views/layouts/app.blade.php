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

        <div class="sidebar-logo">
            <img src="{{ asset('image/laundry-login.png') }}" alt="Laundry">
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('home') }}"
                class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="sidebar-item">
                <i class="bi bi-basket-fill"></i>
                <span>Pemesanan</span>
            </a>

            <a href="#" class="sidebar-item">
                <i class="bi bi-people-fill"></i>
                <span>Pelanggan</span>
            </a>

            <a href="#" class="sidebar-item">
                <i class="bi bi-receipt"></i>
                <span>Transaksi</span>
            </a>

            <a href="{{ route('reward.index') }}"
                class="sidebar-item {{ request()->routeIs('reward.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Reward Stempel</span>
            </a>

            <a href="#" class="sidebar-item">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>

            <a href="#" class="sidebar-item">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan</span>
            </a>

            <a href="#" class="sidebar-item sidebar-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>

        </nav>
    </aside>


    {{-- BAGIAN KANAN --}}
    <div class="dashboard-main">

        {{-- TOPBAR --}}
        <header class="dashboard-topbar">

            <div class="topbar-user">
                <i class="bi bi-person-fill"></i>

                <span>
                    {{ Auth::check() ? Auth::user()->name : 'Bhanu' }}
                </span>
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