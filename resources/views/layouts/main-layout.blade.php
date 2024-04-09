<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIRECT SALES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mystyle.css') }}">
    <style>
        .b-example-vr {
            flex-shrink: 0;
            width: 3px;
            height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar .nav-link span {
                display: none;
            }
        }

        .sidebar {
            background-color: rgba(0, 0, 170, 0.7);
            color: white;
            box-shadow: 0 10px 14px rgba(0, 0, 255, 1);
            padding: 20px;
        }

        .nav-link {
            background-color: rgba(0, 0, 170, 0);
            color: black;
        }

        .nav-link:hover {
            background-color: rgba(0, 0, 170, 0);
            color: white;
        }

        .nav-link.active {
            background-color: green;
            color: black;
        }
    </style>
</head>

<body>
    <main class="d-flex flex-nowrap">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-decoration-none">
            <p href="#" class="nav-item d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <i class="bi bi-menu-up"></i><span class="d-none d-md-inline text-decoration-none"> MAIN MENU</span>
            </p>
            <hr>
            <ul class="nav nav-item flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('main') }}" class="nav-link active" aria-current="page">
                        <i class="bi bi-house"></i><span class="d-none d-md-inline"> Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('main') }}" class="nav-link {{ Request::is('main') ? 'active' : '' }}">
                        <i class="bi bi-house"></i><span class="d-none d-md-inline"> Pembelian</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-backspace"></i><span class="d-none d-md-inline"> Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-backspace"></i><span class="d-none d-md-inline"> Penagihan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-backspace"></i><span class="d-none d-md-inline"> Pembayaran</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="nav-link d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>