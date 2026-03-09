<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    @stack('style')

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>

<div class="container-scroller">

    @if(Route::currentRouteName() !== 'otp.view')
        @include('partials.navbar')
    @endif

    <div class="container-fluid page-body-wrapper">

        @if(Route::currentRouteName() !== 'otp.view')
            @include('partials.sidebar')
        @endif

       <div class="main-panel" @if(Route::is('otp.view')) style="width: 100%; margin-left: 0; padding-top: 0;" @endif>
    <div class="content-wrapper" @if(Route::is('otp.view')) style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #d0c8d2;" @endif>
        @yield('content')
    </div>
    @if(!Route::is('otp.view'))
        @include('partials.footer')
    @endif
</div>

    </div>
</div>

<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

<script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@stack('script')

</body>
</html>