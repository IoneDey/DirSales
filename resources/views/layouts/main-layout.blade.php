<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Sales - Main {{ isset($title) ? ' - ' . $title : '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mystyle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tabelsort.css') }}">
    <style>
        .nav-link.active {
            background-color: transparent !important;
            color: red !important;
        }

        .nav-link {
            color: black !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-info">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-black text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Menu - Main</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="{{ route('main') }}" class="{{ Request::is('/') ? 'active' : '' }} nav-link align-middle px-0">
                                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main') }}" class="{{ Request::is('') ? 'active' : '' }} nav-link align-middle px-0">
                                <i class="fs-4 bi-building"></i> <span class="ms-1 d-none d-sm-inline">Pembelian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main') }}" class="{{ Request::is('') ? 'active' : '' }} nav-link align-middle px-0">
                                <i class="fs-4 bi-cart4"></i> <span class="ms-1 d-none d-sm-inline">Penjualan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main') }}" class="{{ Request::is('') ? 'active' : '' }} nav-link align-middle px-0">
                                <i class="fs-4 bi-receipt"></i> <span class="ms-1 d-none d-sm-inline">Penagihan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('main') }}" class="{{ Request::is('') ? 'active' : '' }} nav-link align-middle px-0">
                                <i class="fs-4 bi-currency-exchange"></i> <span class="ms-1 d-none d-sm-inline">Pembayaran</span>
                            </a>
                        </li>
                    </ul>
                    <hr>
                    @auth
                    <div class="dropdown pb-4">
                        <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fs-4 bi-person-check-fill"></i>
                            <span class="d-none d-sm-inline mx-1">Hi, {{ auth()->User()->username }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('panel') }}">Panel</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Log out</a></li>
                        </ul>
                    </div>
                    @else
                    <div class="dropdown pb-4">
                        <a href="{{ route('login') }}" class="{{ Request::is('login') ? 'active' : '' }} nav-link align-middle px-0">
                            <i class="fs-4 bi-person"></i> <span class="ms-1 d-none d-sm-inline">Login</span>
                        </a>
                    </div>
                    @endauth

                </div>
            </div>


            <div class="col py-3">
                @if(isset($title) && $title) <h2>{{ $title }}</h2>
                @endif
                {{ $slot }}
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>