<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">


<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!--
        Theme mode bootstrap (anti-FOUC):
        Keep auth pages visually consistent with dashboard by restoring saved mode
        before stylesheet parsing and first paint.
    -->
    <script>
        (function() {
            var html = document.documentElement;
            var savedMode = localStorage.getItem('layout-mode') || sessionStorage.getItem('data-layout-mode');
            if (savedMode === 'dark' || savedMode === 'light') {
                html.setAttribute('data-layout-mode', savedMode);
                sessionStorage.setItem('data-layout-mode', savedMode);
            }
        })();
    </script>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row justify-content-center"> <!-- Center the column -->
                    <div class="col-lg-6 col-md-8"> <!-- Column width can be adjusted -->
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                @if (Route::is(['auth.reset.link.get', 'auth.reset.link.post']))
                                    @yield('content')
                                @else
                                    <div class="col-12">
                                        <div class="p-lg-5 p-4">
                                            <div>
                                                <h5 class="text-primary">Welcome Back !</h5>
                                                <p class="text-muted">Sign in to continue to {{ $settings?->app_name ?? '' }}.
                                                </p>
                                            </div>

                                            <div class="mt-4">
                                                @yield('content')
                                            </div>

                                            @if (Route::is('login'))
                                                <div class="mt-5 text-center">
                                                    <p class="mb-0">
                                                    {{-- Don't have an account? --}}
                                                       {{--  <a href="{{ route('auth.signup.get') }}"
                                                            class="fw-semibold text-primary text-decoration-underline">
                                                            Signup
                                                        </a> --}}
                                                    </p>
                                                </div>
                                            @elseif(Route::is('signup.get'))
                                                <div class="mt-5 text-center">
                                                    <p class="mb-0">Already have an account?
                                                        <a href="{{ route('login') }}"
                                                            class="fw-semibold text-primary text-decoration-underline">
                                                            login
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>

        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ $settings?->app_name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!--Swiper sweet alert 2 js-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    @stack('srcipts-bottom')

    {{-- notifications --}}
    @include('backend.partials.notifications')

</body>

</html>


