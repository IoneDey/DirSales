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

        @media (max-width: 360px) {
            .b-example-vr {
                flex-shrink: 0;
                width: 3px;
                height: calc(100vh - 50px);
                overflow-y: auto;
            }
        }

        .sidebar {
            height: 100vh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;

            background-color: rgba(0, 0, 170, 0.7);
            color: white;
            box-shadow: 0 10px 14px rgba(0, 0, 255, 1);
        }

        .content {
            padding-left: calc(var(--sidebar-width) + 5px);
            padding-top: 10px;
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
            background-color: rgba(0, 0, 0, 1);
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            display: inline-block;
            transition: all 0.3s ease;
            width: 150px;
        }

        .content h2 {
            color: black;
            /* Atur warna teks kembali ke hitam */
        }
    </style>
</head>

<body>
    <main class="nav-link d-flex">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-decoration-none">
            <p href="#" class="nav-item align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
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
                        <i class="bi bi-building"></i><span class="d-none d-md-inline"> Pembelian</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-cart4"></i><span class="d-none d-md-inline"> Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-receipt"></i><span class="d-none d-md-inline"> Penagihan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" text-decoration-none>
                        <i class="bi bi-currency-exchange"></i><span class="d-none d-md-inline"> Pembayaran</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="nav-link">
                <a href="#" class="nav-link" text-decoration-none>
                    <i class="bi bi-person"></i><span class="d-none d-md-inline"> Login</span>
                </a>
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

            // Set nilai variabel CSS --sidebar-width dengan lebar sidebar yang terlihat
            document.documentElement.style.setProperty('--sidebar-width', `${sidebarWidth}px`);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>