<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') | Kertas Kerja</title>
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/fontawesome-free/css/all.min.css')}}">
    @yield('css')
    <link rel="stylesheet" href="{{asset('assets/lte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('app/app.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @yield('style')
</head>
<body class="hold-transition layout-top-nav layout-navbar-fixed">
<div class="wrapper">
    @include('layouts.parts.menu')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    @yield('content-header')
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Kertas Kerja
        </div>
        <strong>Copyright &copy; 2020 BPKAD</strong>.
    </footer>
</div>

<script src="{{asset('assets/lte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@yield('js')
<script src="{{asset('assets/lte/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('app/app.js')}}"></script>
@yield('script')
</body>
</html>
