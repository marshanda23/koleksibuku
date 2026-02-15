@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')

<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-home"></i>
    </span> Dashboard
  </h3>

  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        Overview
        <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

{{-- CARD STATISTIK  --}}
<div class="row">

  {{-- Total Buku --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-danger card-img-holder text-white" style="border-radius: 15px;">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">
          Total Koleksi Buku
          <i class="mdi mdi-book-open-variant mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">{{ \App\Models\Buku::count() }}</h2>
        <h6 class="card-text">Terdaftar di Perpustakaan</h6>
      </div>
    </div>
  </div>

  {{-- Total Kategori --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-info card-img-holder text-white" style="border-radius: 15px;">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">
          Kategori Buku
          <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">{{ \App\Models\Kategori::count() }}</h2>
        <h6 class="card-text">Klasifikasi Koleksi</h6>
      </div>
    </div>
  </div>

  {{-- Status User --}}
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-success card-img-holder text-white" style="border-radius: 15px;">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">
          Status Admin
          <i class="mdi mdi-account-check mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">Online</h2>
        <h6 class="card-text">Welcome, {{ Auth::user()->name }}</h6>
      </div>
    </div>
  </div>

</div>

{{-- SECTION CHART --}}
<div class="row">
  <div class="col-md-7 grid-margin stretch-card">
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
      <div class="card-body">
        <div class="clearfix">
          <h4 class="card-title float-start">Statistik Pengunjung</h4>
          <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-end"></div>
        </div>
        <canvas id="visit-sale-chart" class="mt-4"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-5 grid-margin stretch-card">
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
      <div class="card-body">
        <h4 class="card-title">Sumber Trafik</h4>
        <div class="doughnutjs-wrapper d-flex justify-content-center">
          <canvas id="traffic-chart"></canvas>
        </div>
        <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
      </div>
    </div>
  </div>
</div>

{{-- TABLE RECENT BOOKS --}}
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
      <div class="card-body">
        <h4 class="card-title">Buku Terbaru yang Ditambahkan</h4>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Judul Buku</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Pengarang</th>
              </tr>
            </thead>
            <tbody>
              @foreach(\App\Models\Buku::latest()->take(5)->get() as $buku)
              <tr>
                <td class="font-weight-bold">{{ $buku->judul }}</td>
                <td><label class="badge badge-gradient-info">{{ $buku->kode }}</label></td>
                <td>{{ $buku->kategori->nama_kategori }}</td>
                <td>{{ $buku->pengarang }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('script')
<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush