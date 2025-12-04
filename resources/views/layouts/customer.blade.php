{{-- FILE: resources/views/layouts/customer.blade.php --}}
@extends('layouts.frontend')

@push('styles')
    <style>
        /* Customer Area Styles */
        .customer-area {
            display: flex;
            min-height: calc(100vh - 200px);
            gap: 0;
            padding: 0;
            margin: 0;
            background: #f8fafc;
        }

        /* Sidebar */
        .customer-sidebar {
            width: 280px;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 120px;
            height: calc(100vh - 140px);
            overflow-y: auto;
            padding: 2rem 0;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .sidebar-header h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            margin: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #6366f1;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .sidebar-menu-link:hover {
            background: #f8fafc;
            color: #6366f1;
            padding-left: 2rem;
        }

        .sidebar-menu-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            color: #6366f1;
            font-weight: 600;
        }

        .sidebar-menu-link.active::before {
            transform: scaleX(1);
        }

        .sidebar-menu-icon {
            font-size: 1.3rem;
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-menu-text {
            flex: 1;
        }

        .sidebar-menu-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 700;
        }

        /* Content Area */
        .customer-content {
            flex: 1;
            padding: 2rem;
            overflow-x: hidden;
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .content-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
        }

        /* Card Styles */
        .content-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .card-header-custom h5 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Order Card */
        .order-card {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: #6366f1;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 1rem;
        }

        .order-number {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-pending_payment {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-verified {
            background: #ddd6fe;
            color: #5b21b6;
        }

        .status-in_production {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-shipped {
            background: #bfdbfe;
            color: #1e40af;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        /* Mobile Responsive */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 50%;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.5);
            z-index: 999;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            transform: scale(1.1);
        }

        @media (max-width: 992px) {
            .customer-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .customer-sidebar.active {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .customer-content {
                padding: 1rem;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #64748b;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #94a3b8;
            margin-bottom: 2rem;
        }
    </style>
@endpush
@section('content')
    <div class="customer-area">
        {{-- Sidebar --}}
        <aside class="customer-sidebar" id="customerSidebar">
            <div class="sidebar-header">
                <h5>
                    <i class="lni lni-user"></i>
                    {{ Auth::user()->name }}
                </h5>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('customer.dashboard') }}"
                        class="sidebar-menu-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-menu-icon">
                            <i class="lni lni-dashboard"></i>
                        </span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('customer.orders.index') }}"
                        class="sidebar-menu-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                        <span class="sidebar-menu-icon">
                            <i class="lni lni-cart"></i>
                        </span>
                        <span class="sidebar-menu-text">Pesanan Saya</span>
                        @if (isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                            <span class="sidebar-menu-badge">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('customer.order.create') }}"
                        class="sidebar-menu-link {{ request()->routeIs('customer.order.create') ? 'active' : '' }}">
                        <span class="sidebar-menu-icon">
                            <i class="lni lni-plus"></i>
                        </span>
                        <span class="sidebar-menu-text">Buat Pesanan</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('customer.profile') }}"
                        class="sidebar-menu-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                        <span class="sidebar-menu-icon">
                            <i class="lni lni-user"></i>
                        </span>
                        <span class="sidebar-menu-text">Profil Saya</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a href="#" class="sidebar-menu-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="sidebar-menu-icon">
                                <i class="lni lni-exit"></i>
                            </span>
                            <span class="sidebar-menu-text">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Sidebar Overlay untuk Mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        {{-- Content --}}
        <main class="customer-content">
            @yield('customer-content')
        </main>

        {{-- Mobile Menu Toggle --}}
        <button class="mobile-menu-toggle" onclick="toggleSidebar()">
            <i class="lni lni-menu"></i>
        </button>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('customerSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            toggleSidebar();
        });
    </script>
@endsection
