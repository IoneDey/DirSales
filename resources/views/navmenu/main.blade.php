<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="{{ Request::is('/') ? 'active' : '' }} nav-link" href="{{ route('main') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Home
                </a>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shop"></i></div>
                    Pembelian
                </a>
                <a class="{{ Request::is('main/penjualan') ? 'active' : '' }} nav-link" href="{{ route('penjualan') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-comments-dollar"></i></div>
                    Penjualan
                </a>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice"></i></div>
                    Penagihan
                </a>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    Pembayaran
                </a>
                @auth
                @else
                <a class="{{ Request::is('login') ? 'active' : '' }} nav-link" href="{{ route('login') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    Login
                </a>
                @endauth
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="nav small">Logged in as:</div>
            @auth
            {{ auth()->User()->name }}
            @else
            Guest
            @endauth
        </div>
    </nav>
</div>