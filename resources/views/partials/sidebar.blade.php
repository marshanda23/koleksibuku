<nav class="sidebar sidebar-offcanvas" id="sidebar">
<ul class="nav">

{{-- PROFILE --}}
<li class="nav-item nav-profile">
<a href="#" class="nav-link">
<div class="nav-profile-image">
<img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile"/>
<span class="login-status online"></span>
</div>

<div class="nav-profile-text d-flex flex-column">
@auth
<span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
<span class="text-secondary text-small">Administrator</span>
@else
<span class="font-weight-bold mb-2">Guest</span>
<span class="text-secondary text-small">Visitor</span>
@endauth
</div>

<i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
</a>
</li>


@guest
<li class="nav-item">
<a class="nav-link" data-bs-toggle="collapse" href="#user-pages">
<span class="menu-title">User Pages</span>
<i class="menu-arrow"></i>
<i class="mdi mdi-lock menu-icon"></i>
</a>
<div class="collapse" id="user-pages">
<ul class="nav flex-column sub-menu">
<li class="nav-item">
<a class="nav-link" href="{{ route('login') }}">Login</a>
</li>
@if (Route::has('register'))
<li class="nav-item">
<a class="nav-link" href="{{ route('register') }}">Register</a>
</li>
@endif
</ul>
</div>
</li>
@endguest


@auth

{{-- DASHBOARD --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('home') || request()->routeIs('dashboard') ? 'active' : '' }}"
href="{{ route('home') }}">
<span class="menu-title">Dashboard</span>
<i class="mdi mdi-home menu-icon"></i>
</a>
</li>

{{-- KATEGORI --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
href="{{ route('kategori.index') }}">
<span class="menu-title">Kategori</span>
<i class="mdi mdi-format-list-bulleted menu-icon"></i>
</a>
</li>

{{-- BUKU --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
href="{{ route('buku.index') }}">
<span class="menu-title">Data Buku</span>
<i class="mdi mdi-book-open-variant menu-icon"></i>
</a>
</li>

{{-- BARANG --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}"
href="{{ route('barang.index') }}">
<span class="menu-title">Data Barang</span>
<i class="mdi mdi-tag-multiple menu-icon"></i>
</a>
</li>

{{-- BARANG JS --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('barang.js*') ? 'active' : '' }}"
data-bs-toggle="collapse"
href="#barangJSMenu">
<span class="menu-title">Barang JS</span>
<i class="menu-arrow"></i>
<i class="mdi mdi-cube-outline menu-icon"></i>
</a>
<div class="collapse {{ request()->routeIs('barang.js*') ? 'show' : '' }}" id="barangJSMenu">
<ul class="nav flex-column sub-menu">
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('barang.js') ? 'active' : '' }}"
href="{{ route('barang.js') }}">
HTML Table
</a>
</li>
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('barang.js.datatables') ? 'active' : '' }}"
href="{{ route('barang.js.datatables') }}">
DataTables
</a>
</li>
</ul>
</div>
</li>

{{-- POS --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
href="{{ route('pos.index') }}">
<span class="menu-title">Point Of Sales</span>
<i class="mdi mdi-cash-register menu-icon"></i>
</a>
</li>

{{-- WILAYAH --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('wilayah.*') ? 'active' : '' }}"
href="{{ route('wilayah.index') }}">
<span class="menu-title">Wilayah</span>
<i class="mdi mdi-map-marker menu-icon"></i>
</a>
</li>

{{-- SELECT KOTA --}}
<li class="nav-item">
<a class="nav-link {{ request()->routeIs('select.*') ? 'active' : '' }}"
href="{{ route('select.index') }}">
<span class="menu-title">Select Kota</span>
<i class="mdi mdi-city menu-icon"></i>
</a>
</li>

{{-- CETAK SERTIFIKAT --}}
<li class="nav-item">
<a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">
<span class="menu-title">Cetak Sertifikat</span>
<i class="mdi mdi-certificate menu-icon"></i>
</a>
</li>

{{-- CETAK UNDANGAN --}}
<li class="nav-item">
<a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">
<span class="menu-title">Cetak Undangan</span>
<i class="mdi mdi-file-document menu-icon"></i>
</a>
</li>

{{-- KANTIN --}}
<li class="nav-item">
    <a class="nav-link {{ request()->is('kantin*') ? 'active' : '' }}"
       data-bs-toggle="collapse"
       href="#kantinMenu">
        <span class="menu-title">Kantin</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-food menu-icon"></i>
    </a>
    <div class="collapse {{ request()->is('kantin*') ? 'show' : '' }}" id="kantinMenu">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('kantin/order') ? 'active' : '' }}"
                   href="{{ url('/kantin/order') }}">
                    Order Customer
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('kantin/vendor*') ? 'active' : '' }}"
                   href="{{ url('/kantin/vendor/login') }}">
                    Vendor Login
                </a>
            </li>
        </ul>
    </div>
</li>

{{-- CUSTOMER --}}
<li class="nav-item">
    <a class="nav-link {{ request()->is('customer*') ? 'active' : '' }}"
       data-bs-toggle="collapse"
       href="#customerMenu">
        <span class="menu-title">Customer</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-group menu-icon"></i>
    </a>
    <div class="collapse {{ request()->is('customer*') ? 'show' : '' }}" id="customerMenu">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                   href="{{ route('customer.index') }}">
                    Data Customer
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customer.tambah1') ? 'active' : '' }}"
                   href="{{ route('customer.tambah1') }}">
                    Tambah Customer 1
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customer.tambah2') ? 'active' : '' }}"
                   href="{{ route('customer.tambah2') }}">
                    Tambah Customer 2
                </a>
            </li>
        </ul>
    </div>
</li>

{{-- KUNJUNGAN TOKO --}}
<li class="nav-item">
    <a class="nav-link {{ request()->is('kunjungan-toko*') ? 'active' : '' }}"
       href="{{ url('/kunjungan-toko') }}">
        <span class="menu-title">Kunjungan Toko</span>
        <i class="mdi mdi-store menu-icon"></i>
    </a>
</li>

{{-- ANTRIAN --}}
<li class="nav-item">
    <a class="nav-link {{ request()->is('antrian*') ? 'active' : '' }}"
       data-bs-toggle="collapse"
       href="#antrianMenu">
        <span class="menu-title">Antrian</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-ticket-account menu-icon"></i>
    </a>
    <div class="collapse {{ request()->is('antrian*') ? 'show' : '' }}" id="antrianMenu">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('antrian.guest') ? 'active' : '' }}"
                   href="{{ route('antrian.guest') }}">
                    Daftar Antrian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('antrian.admin') ? 'active' : '' }}"
                   href="{{ route('antrian.admin') }}">
                    Admin Antrian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('antrian.papan') ? 'active' : '' }}"
                   href="{{ route('antrian.papan') }}" target="_blank">
                    Papan Antrian
                </a>
            </li>
        </ul>
    </div>
</li>

{{-- SIGN OUT --}}
<li class="nav-item">
<a class="nav-link"
href="{{ route('logout') }}"
onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
<span class="menu-title">Sign Out</span>
<i class="mdi mdi-logout menu-icon"></i>
</a>
</li>

@endauth

</ul>
</nav>