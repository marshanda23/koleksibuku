<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    {{-- SIDEBAR --}}
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
         
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
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

    {{-- user sebelum login --}}
    @guest
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#user-pages" aria-expanded="false" aria-controls="user-pages">
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-lock menu-icon"></i>
      </a>
      <div class="collapse" id="user-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('login') }}"> Login </a></li>
          @if (Route::has('register'))
            <li class="nav-item"> <a class="nav-link" href="{{ route('register') }}"> Register </a></li>
          @endif
        </ul>
      </div>
    </li>
    @endguest

    {{-- auth dashboard setelah login --}}
    @auth
    <li class="nav-item {{ Request::is('home') || Request::is('dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/home') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/kategori') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/buku') }}">
        <span class="menu-title">Data Buku</span>
        <i class="mdi mdi-book-open-variant menu-icon"></i>
      </a>
    </li>
  <li class="nav-item">
  <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">
    <span class="menu-title">Cetak Sertifikat</span>
    <i class="mdi mdi-certificate menu-icon"></i>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">
    <span class="menu-title">Cetak Undangan</span>
    <i class="mdi mdi-file-document menu-icon"></i>
  </a>
</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('logout') }}" 
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="menu-title">Sign Out</span>
        <i class="mdi mdi-logout menu-icon"></i>
      </a>
    </li>
    @endauth
  </ul>
</nav>