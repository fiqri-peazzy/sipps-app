<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('backend/assets/images/sipps.png') }}" type="image/png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/material.css') }}" />

    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css
" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style-preset.css') }}" />


    @stack('styles')
    @livewireStyles

</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr"
    data-pc-theme="light">

    {{-- <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div> --}}

    @include('layouts.sidebar')

    @include('layouts.navbar')

    <div class="pc-container">
        <div class="pc-content">

            @if (isset($header))
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{ $slot }}

        </div>
    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('backend/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('backend/assets/js/script.js') }}"></script>
    <script src="{{ asset('backend/assets/js/theme.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/feather.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script> --}}

    <script>
        layout_change('light');
        font_change('Roboto');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
    </script>
    @stack('scripts')
    @livewireScripts

    @auth
    @if(in_array(auth()->user()->role, ['admin', 'owner', 'keuangan']) && !session()->has('admin_order_notif_shown'))
    @php
        $pendingOrders = \App\Models\Order::where('status', 'paid')
            ->with('user')
            ->orderBy('paid_at', 'asc')
            ->get();
        session(['admin_order_notif_shown' => true]);
    @endphp

    @if($pendingOrders->count() > 0)
    <div id="sipps-notif-backdrop" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.35);z-index:99990;display:flex;align-items:center;justify-content:center;">
        <div id="sipps-notif-box" style="background:#fff;border-radius:20px;width:420px;max-width:calc(100vw - 32px);box-shadow:0 20px 60px rgba(0,0,0,0.18);overflow:hidden;">
            <div style="padding:20px 20px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:15px;font-weight:800;color:#1e293b;margin:0;">&#128276; {{ $pendingOrders->count() }} Pesanan Menunggu Konfirmasi</p>
                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Pesanan sudah dibayar dan perlu dikonfirmasi admin.</p>
                </div>
                <button id="sipps-notif-closebtn" type="button" style="background:#f1f5f9;border:none;border-radius:50%;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">&times;</button>
            </div>
            <div style="padding:14px;max-height:320px;overflow-y:auto;">
                @foreach($pendingOrders as $o)
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 12px;background:#f8fafc;border-radius:12px;margin-bottom:8px;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:12px;font-weight:700;color:#1e293b;">{{ $o->order_number }}</div>
                        <div style="font-size:11px;color:#64748b;margin-top:2px;">{{ $o->user->name ?? '-' }} &middot; <strong>Rp {{ number_format($o->total_harga, 0, ',', '.') }}</strong></div>
                        <div style="font-size:10px;color:#f59e0b;margin-top:2px;font-style:italic;">Menunggu {{ $o->paid_at ? $o->paid_at->diffForHumans() : '-' }}</div>
                    </div>
                    <a href="{{ auth()->user()->role === 'keuangan' ? route('keuangan.detail.pesanan', $o->id) : route('admin.detail.pesanan', $o->id) }}" style="flex-shrink:0;padding:6px 14px;background:#6366f1;color:#fff;border-radius:8px;font-size:11px;font-weight:700;text-decoration:none;white-space:nowrap;">Lihat &rarr;</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
    (function(){
        var backdrop = document.getElementById('sipps-notif-backdrop');
        var closeBtn = document.getElementById('sipps-notif-closebtn');
        var box = document.getElementById('sipps-notif-box');
        if (!backdrop) return;

        function hideNotif() {
            backdrop.style.display = 'none';
        }

        // Close button
        if (closeBtn) {
            closeBtn.addEventListener('click', hideNotif);
        }

        // Click on backdrop (outside box) to close
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) {
                hideNotif();
            }
        });

        // Prevent clicks inside box from closing
        if (box) {
            box.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    })();
    </script>
    @endif
    @endif
    @endauth

</body>

</html>
