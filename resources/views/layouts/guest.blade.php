<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }} - @yield('pageTitle', 'Login')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Aplikasi Penjadwalan Pemesanan Sablon" />
    <meta name="author" content="Your Name" />

    <link rel="icon" href="{{ asset('backend/assets/images/favicon.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style-preset.css') }}" />
</head>

<body>
    {{-- <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div> --}}

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('backend/assets/js/script.js') }}"></script>
    <script src="{{ asset('backend/assets/js/theme.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
    </script>

    <script>
        font_change('Roboto');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>
</body>

</html>
