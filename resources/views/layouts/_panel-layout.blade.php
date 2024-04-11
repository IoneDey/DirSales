<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIRECT SALES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mystyle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tabelsort.css') }}">
    <style>
        .b-example-vr {
            flex-shrink: 0;
            width: 3px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar {
            height: var(--screen-height);
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 0 10px 14px rgba(0, 0, 0, 1);
        }

        .content {
            padding-left: calc(var(--sidebar-width) + 5px);
            padding-top: 5px;
        }

        @media (max-width: 768px) and (orientation: portrait) {
            .content {
                padding-left: calc(var(--sidebar-width) + 5px);
                padding-top: 5px;
            }
        }
    </style>
</head>

<body>
    <main class="d-flex flex-nowrap">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-gear me-2"></i><span class="d-none d-md-inline">C. PANEL</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('panel') }}" class="nav-link {{ Request::is('panel') ? 'active' : '' }}" aria-current="page">
                        <i class="bi bi-house"></i><span class="d-none d-md-inline"> Home</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown">
                        @php
                        $isActive = Request::is('barang') || Request::is('kota') || Request::is('pt') || Request::is('user');
                        @endphp
                        <a href="#" class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list-check"></i><span class="d-none d-md-inline" text-decoration-none> Setup</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow dropdown-menu-end">
                            <li><a class="dropdown-item {{ Request::is('barang') ? 'active' : '' }}" href="{{ route('barang') }}">Barang</a></li>
                            <li><a class="dropdown-item {{ Request::is('kota') ? 'active' : '' }}" href="{{ route('kota') }}">Kota</a></li>
                            <li><a class="dropdown-item {{ Request::is('pt') ? 'active' : '' }}" href="{{ route('pt') }}">PT</a></li>
                            <li><a class="dropdown-item" href="#">User</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" class="nav-link text-white" text-decoration-none>
                        <i class="bi bi-backspace"></i><span class="d-none d-md-inline"> Main Menu</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-check"></i><span class="d-none d-md-inline"> Helo</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
        <div class="b-example-divider b-example-vr"></div>

        <div class="content container-fluid">
            <div class="row">
                <div class="col">
                    <h2>{{ $title }}</h2>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarWidth = sidebar.offsetWidth;
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;

            document.documentElement.style.setProperty('--sidebar-width', `${sidebarWidth}px`);


            document.documentElement.style.setProperty('--screen-width', `${screenWidth}px`);
            document.documentElement.style.setProperty('--screen-height', `${screenHeight}px`);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>