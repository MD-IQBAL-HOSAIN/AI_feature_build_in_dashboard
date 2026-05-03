<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!--
        Theme mode bootstrap (anti-FOUC):
        Apply persisted mode before CSS loads so refresh does not flash light mode.
        localStorage keeps long-term preference; sessionStorage keeps compatibility with template scripts.
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
    @stack('styles-top')
    @include('backend.partials.style')
    @stack('styles-bottom')

</head>

<body>
    <div id="layout-wrapper">
        <!-- header -->
        @include('backend.partials.header')

        <!-- ========== App Menu ========== -->

        <!-- Left Sidebar Start -->
        @include('backend.partials.sidebar')
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        <div class="main-content">
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('backend.partials.footer')
        </div>
        <!-- end main content-->

    </div>
    @stack('scripts-top')
    <!-- END layout-wrapper -->
    @include('backend.partials.script')
    @stack('scripts-bottom')
    @include('backend.partials.notifications')

</body>
</html>
