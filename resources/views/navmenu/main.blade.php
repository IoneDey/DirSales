<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="{{ Request::is('/') ? 'active' : '' }} nav-link" href="{{ route('main') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Home
                </a>

                <!-- menu pembelian -->
                @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR']))
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shop"></i></div>
                    Pembelian
                </a>
                @endif

                <!-- menu penjualan -->
                @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','ADMIN 2','LOCK']))
                @php
                $isActivePenjualan = Request::is('main/penjualan') || Request::is('main/penjualanret') || Request::is('main/penjualanvalidasi') || Request::is('main/penjualanreport') || Request::is('main/returpenjualanreport');
                @endphp
                <a class="{{ $isActivePenjualan ? 'active' : '' }} nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsPenjualan" aria-expanded="false" aria-controls="collapseLayoutsPenjualan">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Penjualan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <!-- menu item penjualan -->
                <div class="collapse" id="collapseLayoutsPenjualan" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','ADMIN 2']))
                        <a class="{{ Request::is('main/penjualan') ? 'active' : '' }} nav-link" href="{{ route('penjualan') }}">Entry Penjualan</a>
                        <a class="{{ Request::is('main/penjualanret') ? 'active' : '' }} nav-link" href="{{ route('penjualanret') }}">Entry Retur Penjualan</a>
                        @endif
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','LOCK']))
                        <a class="{{ Request::is('main/penjualanvalidasi') ? 'active' : '' }} nav-link" href="{{ route('penjualanvalidasi') }}">Validasi Penjualan</a>
                        @endif
                        <hr>
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','ADMIN 2','LOCK']))
                        <a class="{{ Request::is('main/penjualanreport') ? 'active' : '' }} nav-link" href="{{ route('penjualanreport') }}">Laporan Penjualan</a>
                        @endif
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','ADMIN 2']))
                        <a class="{{ Request::is('main/returpenjualanreport') ? 'active' : '' }} nav-link" href="{{ route('returpenjualanreport') }}">Laporan Retur Penjualan</a>
                        @endif
                    </nav>
                </div>
                @endif

                <!-- menu penagihan -->
                @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','PENAGIHAN','ADMIN 2']))
                @php
                $isActivePenagihan = Request::is('main/penagihan/*') || Request::is('main/penagihanreport') || Request::is('main/penagihanreportnota') || Request::is('main/penagihanreporttim');
                @endphp
                <a class="{{ $isActivePenagihan ? 'active' : '' }} nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsPenagihan" aria-expanded="false" aria-controls="collapseLayoutsPenagihan">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice"></i></div> Penagihan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                @endif
                <!-- menu item penagihan -->
                <div class="collapse" id="collapseLayoutsPenagihan" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR','PENAGIHAN','ADMIN 2']))
                        <a class="{{ Request::is('main/penagihan/*') ? 'active' : '' }} nav-link" href="{{ route('penagihan',['id' => 0]) }}">Entry Penagihan</a>
                        <a class="{{ Request::is('main/penagihanreport') ? 'active' : '' }} nav-link" href="{{ route('penagihanreport') }}">Laporan Penagihan</a>
                        <a class="{{ Request::is('main/penagihanreportnota') ? 'active' : '' }} nav-link" href="{{ route('penagihanreportnota') }}">Laporan Rekap Sisa Penagihan/Nota</a>
                        <a class="{{ Request::is('main/penagihanreporttim') ? 'active' : '' }} nav-link" href="{{ route('penagihanreporttim') }}">Laporan Rekap Penagihan/Tim</a>
                        @endif
                    </nav>
                </div>

                @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR']))
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    Pembayaran
                </a>
                @endif

                <!-- menu dashboard -->
                @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR']))
                @php
                $isActiveDashboard = Request::is('main/penjualandashboard');
                @endphp
                <a class="{{ $isActiveDashboard ? 'active' : '' }} nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsDashboard" aria-expanded="false" aria-controls="collapseLayoutsDashboard">
                    <div class="sb-nav-link-icon"><i class="bi bi-speedometer2"></i></div> Dashboard
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                @endif
                <!-- menu item dashboard -->
                <div class="collapse" id="collapseLayoutsDashboard" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if (in_array((auth()->user()->roles ?? ''), ['SUPERVISOR']))
                        <a class="{{ Request::is('main/penjualandashboard') ? 'active' : '' }} nav-link" href="{{ route('penjualandashboard') }}">Penjualan</a>
                        @endif
                    </nav>
                </div>

                <!-- menu login -->
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
            <div class="nav small">Logged in as: {{ auth()->user()->roles ?? '' }}</div>
            @auth
            @if (auth()->user()->image)
            <img src="{{ asset('storage/' . auth()->user()->image) }}" class="img-fluid rounded-circle" style="object-fit: cover; width: 25px; height: 25px;" alt="Profile Picture">
            @else
            <img src="{{ asset('img/profile-kosong.webp') }}" class="img-fluid rounded-circle" style="object-fit: cover; width: 25px; height: 25px;" alt="Profile Picture">
            @endif
            <a href="{{ route('profile') }}">{{ auth()->User()->name }}</a>
            @else
            Guest
            @endauth
        </div>
    </nav>
</div>