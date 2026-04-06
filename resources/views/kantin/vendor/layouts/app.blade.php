<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vendor Kantin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="sidebar-icon-only">
<div class="container-scroller">

    {{-- NAVBAR --}}
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo" href="{{ url('/kantin/vendor/dashboard') }}">
                <span class="text-white fw-bold">🍱 Vendor Kantin</span>
            </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-store text-primary fs-4"></i>
                        <span class="ms-2">{{ session('vendor_nama') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ url('/kantin/vendor/logout') }}">
                        <i class="mdi mdi-logout fs-4"></i>
                        <span class="ms-1">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">

        {{-- SIDEBAR --}}
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">

                <li class="nav-item nav-profile">
                    <a href="#" class="nav-link">
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2">{{ session('vendor_nama') }}</span>
                            <span class="text-secondary text-small">Vendor</span>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kantin/vendor/dashboard') ? 'active' : '' }}"
                       href="{{ url('/kantin/vendor/dashboard') }}">
                        <span class="menu-title">Pesanan Lunas</span>
                        <i class="mdi mdi-shopping menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kantin/vendor/menu') ? 'active' : '' }}"
                       href="{{ url('/kantin/vendor/menu') }}">
                        <span class="menu-title">Kelola Menu</span>
                        <i class="mdi mdi-food menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ url('/kantin/vendor/logout') }}">
                        <span class="menu-title">Logout</span>
                        <i class="mdi mdi-logout menu-icon"></i>
                    </a>
                </li>

            </ul>
        </nav>

        {{-- CONTENT --}}
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
@stack('scripts')
</body>
</html>