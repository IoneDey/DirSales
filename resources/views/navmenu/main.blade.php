<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="{{ Request::is('/') ? 'active' : '' }} nav-link" href="{{ route('main') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Home
                </a>
                @switch((auth()->user()->roles ?? ''))
                @case('SUPERVISOR')
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shop"></i></div>
                    Pembelian
                </a>
                <!-- penjualan -->
                @php
                $isActive = Request::is('main/penjualan') || Request::is('main/penjualanentry') || Request::is('main/penjualanreport');
                @endphp
                <a class="{{ $isActive ? 'active' : '' }} nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Penjualan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="{{ Request::is('main/penjualan') ? 'active' : '' }} nav-link" href="{{ route('penjualan') }}">Entry Penjualan</a>
                        <a class="{{ Request::is('/') ? 'active' : '' }} nav-link" href="{{ route('main') }}">Validasi Penjualan</a>
                        <a class="{{ Request::is('main/penjualanreport') ? 'active' : '' }} nav-link" href="{{ route('penjualanreport') }}">Laporan Penjualan</a>
                    </nav>
                </div>
                <!-- end penjualan -->
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice"></i></div>
                    Penagihan
                </a>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    Pembayaran
                </a>
                @break
                @case('SPV ADMIN')
                @case('ADMIN')
                <!-- penjualan -->
                @php
                $isActive = Request::is('main/penjualan') || Request::is('main/penjualanentry') || Request::is('main/penjualanreport');
                @endphp
                <a class="{{ $isActive ? 'active' : '' }} nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Penjualan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="{{ Request::is('main/penjualan') ? 'active' : '' }} nav-link" href="{{ route('penjualan') }}">Entry Penjualan</a>
                        @if ((auth()->user()->roles ?? '') == 'SPV ADMIN')
                        <a class="{{ Request::is('panel/kota') ? 'active' : '' }} nav-link" href="{{ route('kota') }}">Validasi Penjualan</a>
                        @endif
                        <a class="{{ Request::is('panel/provinsi') ? 'active' : '' }} nav-link" href="{{ route('provinsi') }}">Laporan Penjualan</a>
                    </nav>
                </div>
                <!-- end penjualan -->
                @break

                @default
                <!-- kosong -->
                @auth
                @else
                <a class="{{ Request::is('login') ? 'active' : '' }} nav-link" href="{{ route('login') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    Login
                </a>
                @endauth
                @endswitch

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="nav small">Logged in as: {{ auth()->user()->roles ?? '' }}</div>
            @auth
            @if (auth()->user()->image)
            <img src="{{ asset('storage/' . auth()->user()->image) }}" class="img-fluid rounded-circle" style="object-fit: cover; width: 25px; height: 25px;" alt="Profile Picture">
            @else
            <img src="{{ asset('img/profile-kosong.webp') }}" class="img-fluid rounded-circle" style="object-fit: cover; width: 25px; height: 25px;" alt="Profile Picture">
            @endif
            {{ auth()->User()->name }}
            @else
            Guest
            @endauth
        </div>
    </nav>
</div>