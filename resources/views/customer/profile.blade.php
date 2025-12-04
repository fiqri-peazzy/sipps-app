{{-- FILE: resources/views/customer/profile.blade.php --}}
@extends('layouts.customer')

@section('customer-content')
    <div class="content-header">
        <h1 class="content-title">
            <i class="lni lni-user"></i>
            Profil Saya
        </h1>
        <p class="content-subtitle">Informasi akun dan detail pribadi Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="lni lni-information"></i>
                        Informasi Pribadi
                    </h5>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong style="color: #64748b;">Nama Lengkap</strong>
                    </div>
                    <div class="col-md-8">
                        <p style="margin: 0; color: #1e293b; font-weight: 600;">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong style="color: #64748b;">Email</strong>
                    </div>
                    <div class="col-md-8">
                        <p style="margin: 0; color: #1e293b; font-weight: 600;">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong style="color: #64748b;">Telepon</strong>
                    </div>
                    <div class="col-md-8">
                        <p style="margin: 0; color: #1e293b; font-weight: 600;">
                            {{ Auth::user()->phone ?? 'Belum ditambahkan' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong style="color: #64748b;">Alamat</strong>
                    </div>
                    <div class="col-md-8">
                        <p style="margin: 0; color: #1e293b; font-weight: 600;">
                            {{ Auth::user()->address ?? 'Belum ditambahkan' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong style="color: #64748b;">Terdaftar Sejak</strong>
                    </div>
                    <div class="col-md-8">
                        <p style="margin: 0; color: #1e293b; font-weight: 600;">
                            {{ Auth::user()->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <hr style="margin: 2rem 0;">

                <button class="btn-primary-custom">
                    <i class="lni lni-pencil"></i> Edit Profil
                </button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card text-center">
                <div
                    style="width: 120px; height: 120px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 3rem; color: #fff; font-weight: 700;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h4 style="color: #1e293b; font-weight: 700; margin-bottom: 0.5rem;">{{ Auth::user()->name }}</h4>
                <p style="color: #64748b; margin: 0;">Customer</p>
            </div>

            <div class="content-card mt-3">
                <h6 style="font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Statistik Akun</h6>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span style="color: #64748b;">Total Pesanan</span>
                    <strong style="color: #1e293b;">{{ $totalOrders ?? 0 }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span style="color: #64748b;">Pesanan Selesai</span>
                    <strong style="color: #10b981;">{{ $completedOrders ?? 0 }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Total Belanja</span>
                    <strong style="color: #6366f1;">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
@endsection
