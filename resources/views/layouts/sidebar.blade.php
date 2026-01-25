<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
                <img src="{{ asset('backend/assets/images/sipps.png') }}" alt="SIPPS" class="logo"
                    style="max-height: 45px; object-fit: contain;" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Dashboard</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard Admin</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Manajemen Pesanan</label>
                    <i class="ti ti-shopping-cart"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.data.pesanan') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                        <span class="pc-mtext">Data Pesanan</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.users.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Manajemen User</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Manajemen Produksi</label>
                    <i class="ti ti-tools"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.produk.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-package"></i></span>
                        <span class="pc-mtext">Data Produksi</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.penjadwalan.prioritas') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-calendar-time"></i></span>
                        <span class="pc-mtext">Metode DPS</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.bobot-prioritas.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-settings-automation"></i></span>
                        <span class="pc-mtext">Bobot Prioritas</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Produksi & Pengiriman</label>
                    <i class="ti ti-package"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.production.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-settings"></i></span>
                        <span class="pc-mtext">Produksi</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.shipping.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-truck"></i></span>
                        <span class="pc-mtext">Proses Pengiriman</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.returns') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-refresh"></i></span>
                        <span class="pc-mtext">Return Barang</span>
                        @php
                            $pendingReturns = \App\Models\CustomerReturn::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingReturns > 0)
                            <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingReturns }}</span>
                        @endif
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Marketing & Konten</label>
                    <i class="ti ti-world"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.portfolio.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-layout-grid"></i></span>
                        <span class="pc-mtext">Portfolio Kerja</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Laporan & Analitik</label>
                    <i class="ti ti-file-analytics"></i>
                </li>
                <li class="pc-item pc-hasmenu {{ request()->routeIs('admin.reports.*') ? 'pc-trigger active' : '' }}">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
                        <span class="pc-mtext">Laporan</span>
                        <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu"
                        style="{{ request()->routeIs('admin.reports.*') ? 'display: block;' : '' }}">
                        <!-- Export Reports -->
                        <li class="pc-item {{ request()->routeIs('admin.reports.orders') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('admin.reports.orders') }}">
                                <i class="ti ti-file-invoice"></i> Laporan Pesanan
                            </a>
                        </li>
                        <li class="pc-item {{ request()->routeIs('admin.reports.dps-performance') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('admin.reports.dps-performance') }}">
                                <i class="ti ti-chart-line"></i> Kinerja DPS
                                <span class="badge bg-success badge-sm ms-2" style="font-size: 8px;">Skripsi</span>
                            </a>
                        </li>
                        <li class="pc-item {{ request()->routeIs('admin.reports.comparison') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('admin.reports.comparison') }}">
                                <i class="ti ti-arrows-exchange"></i> FCFS vs DPS
                                <span class="badge bg-success badge-sm ms-2" style="font-size: 8px;">Skripsi</span>
                            </a>
                        </li>

                        <li class="pc-item">
                            <hr class="my-2 border-top border-secondary opacity-25">
                        </li>

                        <!-- Dashboard Analytics -->
                        <li class="pc-item {{ request()->routeIs('admin.reports.dashboard') ? 'active' : '' }}">
                            <a class="pc-link" href="{{ route('admin.reports.dashboard') }}">
                                <i class="ti ti-dashboard"></i> Dashboard Analitik
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
