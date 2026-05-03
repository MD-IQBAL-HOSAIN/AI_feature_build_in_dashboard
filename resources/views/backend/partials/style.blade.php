<style>
    /* CK Editor minimum height */
    .ck-editor__editable_inline {
        min-height: 200px;
    }

    /* Keep CKEditor content direction in sync with selected RTL language. */
    .is-rtl-language .ck-editor__editable,
    .is-rtl-language .ck-editor__editable_inline {
        direction: rtl;
        text-align: right;
    }

    .is-rtl-language .ck-editor__editable p,
    .is-rtl-language .ck-editor__editable li,
    .is-rtl-language .ck-editor__editable h1,
    .is-rtl-language .ck-editor__editable h2,
    .is-rtl-language .ck-editor__editable h3,
    .is-rtl-language .ck-editor__editable h4,
    .is-rtl-language .ck-editor__editable h5,
    .is-rtl-language .ck-editor__editable h6,
    .is-rtl-language .ck-editor__editable blockquote {
        direction: rtl;
        text-align: right;
    }

    .is-rtl-language .ck-editor__editable * {
        unicode-bidi: plaintext;
    }

    /*
    *Sidebar scroll containment - prevent scroll from leaking to page-content start
    */
    .app-menu.navbar-menu {
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE/Edge */
    }

    .app-menu.navbar-menu::-webkit-scrollbar {
        display: none;
        /* Chrome/Safari/Opera */
    }

    .app-menu.navbar-menu #scrollbar {
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .app-menu.navbar-menu #scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide SimpleBar scrollbar track inside sidebar */
    .app-menu.navbar-menu .simplebar-track.simplebar-vertical {
        display: none !important;
    }

    .app-menu.navbar-menu .simplebar-content-wrapper {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .app-menu.navbar-menu .simplebar-content-wrapper::-webkit-scrollbar {
        display: none;
    }

    /* For collapsed sidebar, keep scrollbar container scrollable */
    [data-layout=vertical][data-sidebar-size=sm] .navbar-menu #scrollbar,
    [data-layout=vertical][data-sidebar-size=sm-hover] .navbar-menu #scrollbar {
        overflow-y: auto !important;
        overscroll-behavior: contain;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    [data-layout=vertical][data-sidebar-size=sm] .navbar-menu #scrollbar::-webkit-scrollbar,
    [data-layout=vertical][data-sidebar-size=sm-hover] .navbar-menu #scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Shared sidebar brand layout for the custom "logo + site title" treatment. */
    .navbar-brand-box .logo .logo-lg {
        display: flex;
        align-items: center;
        min-height: 22px;
    }

    .sidebar-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        max-width: 180px;
        overflow: hidden;
    }

    .sidebar-brand__logo {
        display: block;
        width: auto;
        height: 28px;
        object-fit: contain;
        flex: 0 0 auto;
    }

    /* Compact uploaded assets behave like a mark/icon and stay visually small. */
    .sidebar-brand__logo.is-mark {
        width: 22px;
        height: 22px;
        max-width: 22px;
    }

    /* Wide uploaded assets keep more horizontal room for full logo artwork. */
    .sidebar-brand__logo.is-wide {
        max-width: 132px;
    }

    .sidebar-brand__mark {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 24px;
        color: #0b84ff;
        flex: 0 0 20px;
    }

    .sidebar-brand__mark i:first-child {
        position: absolute;
        top: -1px;
        font-size: 1.45rem;
        line-height: 1;
    }

    .sidebar-brand__mark i:last-child {
        position: absolute;
        top: 6px;
        font-size: 1.45rem;
        line-height: 1;
        opacity: 0.88;
    }

    .sidebar-brand__text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    /* Respect the template's dark/light logo wrappers for text color. */
    .logo-dark .sidebar-brand__text {
        color: #1f2937;
    }

    .logo-light .sidebar-brand__text {
        color: #f8fbff;
    }

    /* Global backend topbar skin. The client wants the same custom brand + center nav
       to remain visible while navigating any sidebar page, not only the dashboard route. */
    .dashboard-page-topbar {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .dashboard-page-topbar .layout-width {
        max-width: none;
        padding: 0 18px;
    }

    .dashboard-page-topbar .navbar-header {
        min-height: 72px;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    /* Reserve stable room for the left brand block so the center nav stays centered. */
    .dashboard-page-topbar .navbar-header>.d-flex:first-child {
        flex: 0 0 auto;
        min-width: 320px;
    }

    .dashboard-page-topbar .navbar-header>.d-flex.align-items-center {
        flex: 0 0 auto;
    }

    .dashboard-header-brand {
        align-items: center;
        gap: 14px;
        margin-left: 18px;
        text-decoration: none;
    }

    .dashboard-header-brand__logo {
        display: block;
        width: auto;
        height: 38px;
        max-width: 240px;
        object-fit: contain;
    }

    .dashboard-header-brand__logo.is-mark {
        width: 28px;
        height: 28px;
        max-width: 28px;
        flex: 0 0 28px;
    }

    .dashboard-header-brand__logo.is-wide {
        height: 38px;
        max-width: 240px;
    }

    .dashboard-header-brand__mark {
        position: relative;
        display: inline-flex;
        width: 24px;
        height: 28px;
        align-items: center;
        justify-content: center;
        color: #0b84ff;
    }

    .dashboard-header-brand__mark i:first-child {
        position: absolute;
        top: -2px;
        font-size: 1.8rem;
        line-height: 1;
    }

    .dashboard-header-brand__mark i:last-child {
        position: absolute;
        top: 8px;
        font-size: 1.8rem;
        line-height: 1;
        opacity: 0.88;
    }

    .dashboard-header-brand__text {
        color: #1f2937;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        line-height: 1;
    }

    .dashboard-header-nav {
        flex: 1 1 auto;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 0;
        margin: 0 48px;
    }

    .dashboard-header-nav__link {
        padding: 9px 14px;
        border-radius: 999px;
        color: #7a8598;
        font-size: 0.9rem;
        line-height: 1;
        text-decoration: none;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .dashboard-header-nav__link:hover,
    .dashboard-header-nav__link.is-active {
        color: #0b84ff;
        background: rgba(11, 132, 255, 0.1);
    }

    .dashboard-header-user {
        padding: 6px 12px !important;
        border: 1px solid rgba(127, 140, 160, 0.22) !important;
        border-radius: 999px !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }

    .dashboard-header-user .header-profile-user {
        width: 24px;
        height: 24px;
    }

    .dashboard-header-user__arrow {
        margin-left: 6px;
        color: #6d7a8f;
        font-size: 1rem;
    }

    html[data-layout-mode="dark"] .dashboard-header-nav__link {
        color: rgba(226, 234, 245, 0.82);
    }

    html[data-layout-mode="dark"] .dashboard-header-nav__link:hover,
    html[data-layout-mode="dark"] .dashboard-header-nav__link.is-active {
        color: #1895ff;
        background: rgba(24, 149, 255, 0.1);
    }

    html[data-layout-mode="dark"] .dashboard-header-user {
        border-color: rgba(255, 255, 255, 0.08) !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }

    html[data-layout-mode="dark"] .dashboard-header-user__arrow {
        color: #8290a6;
    }

    html[data-layout-mode="dark"] .dashboard-page-topbar,
    html[data-layout-mode="dark"] .dashboard-page-topbar .navbar-header {
        background: #0d0d0f !important;
    }

    html[data-layout-mode="dark"] .dashboard-header-brand__text {
        color: #f4f7fb;
    }

    html[data-layout-mode="light"] .dashboard-page-topbar,
    html[data-layout-mode="light"] .dashboard-page-topbar .navbar-header {
        background: #ffffff !important;
        border-bottom-color: rgba(15, 23, 42, 0.08);
    }

    @media (max-width: 1199.98px) {
        .dashboard-page-topbar .navbar-header>.d-flex:first-child {
            min-width: auto;
        }

        .dashboard-header-brand {
            display: none !important;
        }

        .dashboard-header-nav {
            display: none !important;
        }
    }

    /*
    *Sidebar scroll containment - prevent scroll from leaking to page-content end
    */
</style>

<!-- App favicon -->
<link rel="shortcut icon" href="{{ $settings->icon ? asset($settings->icon) : asset('assets/images/favicon.ico') }}">

<!-- jsvectormap css -->
<link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

<!--Swiper slider css-->
<link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css" />

<!-- yajra datatable -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
{{-- Toastr CSS --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

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
{{-- toaster css --}}
<link href="{{ asset('backend/css/toastr.css') }}" rel="stylesheet" />

{{-- dropify css --}}
<link rel="stylesheet" href="{{ asset('backend/css/dropify.min.css') }}">

{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="{{ asset('backend/custom_downloaded_file/sweetalert2.min.css') }}">

{{-- toastr start --}}
<style>
    /* @import url('/public/backend/custom_downloaded_file/css2.css'); */

    #btnSuccess {
        background-color: #1bc5bd;
        border-color: #1bc5bd;
    }

    #btnInfo {
        background-color: #187de4;
        border-color: #187de4;
    }

    #btnWarning {
        background-color: #ee9d01;
        border-color: #ee9d01
    }

    #btnError {
        background-color: #ee2d41;
        border-color: #ee2d41;
    }

    #btnSuccess,
    #btnInfo,
    #btnWarning,
    #btnError {
        color: #fff;
        border-radius: 0.5rem;
        font-weight: 400 !important;
        font-size: 0.765rem;
        width: 90px;
        height: 36px;
        margin: 3px;
        cursor: pointer;
    }

    .toast-success,
    .toast-info,
    .toast-warning,
    .toast-error {
        width: 300px !important;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        border-radius: 1rem !important;
        background-color: #edf1fd;
        color: #01081e !important;
        border-color: transparent !important;
    }

    button.toast-close-button {
        padding: 0;
        cursor: pointer;
        border: 0;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background-color: #ccd7fc !important;
        -webkit-appearance: none;
    }

    .toast-close-button {
        position: relative;
        right: -0.3em;
        top: -0.3em;
        float: right;
        font-size: 20px;
        text-shadow: 0 1px 0 #ffffff;
        opacity: 0.8;
        -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
        filter: alpha(opacity=80);
        line-height: 1;
        color: #000617;
        font-weight: 500;
    }

    #toast-container>.toast-success {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAKLSURBVHgBzZZNbtpAFMffjMFRQY28hFKpzg1gGZFKWGkqdZX2BHVOUHIC4AQ0J6g5QZVVpDTUqC10V3GDelGqSF3UG1cqgZm+GYIVbPAHoCp/CWY89sxP78289wbgP4tAShW+XugwyeiiP6Xc/VV9PkwzPxao2e+1fHbXBEqOgfEyztDCq5AecN4ZVQ+tuPUigY8H9hvOWHMpZLkc/LWiwEuBwqqcqrUJMBPWk+WN6alrGG4sULpQfWjjqzJsJDL0xsQIQmnws7y6294cJsTLeZW1g6MLwFK/a2JjwvZkFgfd+kogqgFbFuXQ0GxbCwFvrdNhHXHew3+Lz05pUNqD7MQMAYGT17AebTg6eGZMx+PWqhijVDme9zP+KOE1SItCixhVXom+oqp4sld4iHP/EEoLS/3LcsSqrnQVtsFXaFHret9wHvXtBkRvh1b81n3iA6eMrM4kBNw/Y1qZKrSyuEf8TGSUwucPNUwQTYiR4rE9HxgjPb/DGsISytiJRCHYyynNgn2hK5S+gxSaATMZJ/IrDvXip6uXP54e9QjjZ4xSw60YLu5bnCt9/cXKIlr/YGFY/MYmKkm7eBIr18YLRzzIxM7ZW0godL9k3Q2LuLqmKTs70n3ClbKKJBWRcQqLQJiex07kvFYafLRlCCQvWcKYjs+ed3RMPzcq+w6QYqEkLDxgP6uHe/Nn30JHlBEOLdiyRKwGnhdV+nJl4ZVhzTQXkoWH5eTuQCgOszdKXRRP2Fh86OXoaXA0BBSuzWKlFpciWJvFO5gYZKwGX0VeokTJwk1vkKRlS+Zb1hodHK2Mz0T3UlkrRflaVlEkhOMWkHMvT61lVqUGLsIvy+Q22WcyE8fZn2Wee6t/lXrvcWjggekAAAAASUVORK5CYII=') !important;
    }

    #toast-container>.toast-info {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJaSURBVHgBvVZNbtNQEJ55idd4hSphKnOCkhM0PQHpCUhuADsWLTgkQuwQJyCcoOEEpSfAnKBW6cK0m6wb+U1nXu04bdLkTRT1kxLnOR5/b/7eNwge2Dn61ybEN4agbRFiBAjlPgFM+HfK1xSJfuXDl7/XvQtXEh1fdvnyiT8xeAEzgKKfD3ZHjz6xlOjDeYzN4IQQXsNGYOLpzUH+9VX28B+zQPbx8i0FwZ/NyQQUI7/j+dFFZ2Er8wt5wKA5gW0CoZt/jn7WyxISRvGsKojt8cGEptNWFd46pEFw6kfG+SE6KBrQ4kW27mmu4FDqoVo7wrIaY/AB2Z6U/3USpZbsey8Troed44vujBDuSt8LSDgrJs63IvwNx4HS1IB46msmObEAfUR8RkTvVDnnVDTZoEPgD5cTgG9svPrUWG7babLZntJowl8Ze5hKv/Gttr817htdg+MIucT/D6NWPnjR4/PzLyhg+Bw2ur6jLrfPj9lKH53QgBI07xVqwnkH43KiACKlcnXVrYRUuOHvTGNUFMYRsj6qD3fRTWMsnfkayA6vv0RpudoHJSQdEtKxtwHvsGZ3LaECb3hsyrEg8zGoCiZMzkMkrV5iJlxVlfa9TCym9W5xLB/wRuE4HGE+iEZ8AKRrbRpl3ibirf1OQJ5esnflnFMLcMICXGxfgN1k12ABTh4IsNxA8NM3FaHoZ1IPU/dOGnGbRfVQexgsJXLvsL2r4e69PC8fEzm8UASikTFsAK7glJrTw3nPVhLOiJ9qEF4gllGfxZPbYE/kbH7UF23kX2fS1D6j/i3BTfaqkKQ1ngAAAABJRU5ErkJggg==') !important;
    }

    #toast-container>.toast-warning {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAHbSURBVHgBxZZPTsJAFMa/NxDEjYJE1/UGcgOP4BEwARNXeAP0BMSVCzWNJxBPIDfQG8COhSSwJBH7fFNbaKV/ZsA/vwVppzPz5Zt58zHAH0OwZOCisjNHgxWmBYVe9RRTm/FWgiMXzpZHz/LoBE1DUly3EVWwoOSpTkRM43hzXMACY4eBu0HCp6m4PDR1aeywzOSmfKrYuDQSHN9LkTCOFw3Mr/I7DF9JUXt0E1vqzQQVqBN5ne41UdfFop+DtkqppDpmc+Wg3SFeKL5IsGeLfSNw4+02sgrrCn5zl0mhkN83U3DiqjZgtjc+ss95LlMF9TFgj63OmCbPZapgGZbuQsRlsO/mguu6W05KnYlkbvK3BIII24TUyFsR1O50iWNDdBgkuVwR/AF3IYmRFwtvXdIF5f/9ZOGH9UwmlDB/0RPn9Y0Ge8yhiLnIp8IiZCDm9/U81Y02LBzqUpbqMhG0Zv7B9YMz6MBfOrSJMDkyV8z8YNq/WKTuUgeJAZ2hxk+1Fi5rTTSIuWc2Zhl5Sh8DG3dQtLuYh+jIdFgYeTS+w4kieoQdQ3wVTF7RxNhmdopSNlaDAhyswexd8kDfLRG5LvwWUmTX1XO5VoYNemmJYbwnNnhAf7+FPv6DT2e/n0VFnPgUAAAAAElFTkSuQmCC') !important;
    }

    #toast-container>.toast-error {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAIlSURBVHgB3VbBbtNAEH2zTugFFIPgwAlzA4mK8AdrvgC+APiSKl/S9AvoH8SfEEQP3PCNAwjcA6IQeYcdYxtvbK/jNr30SVG86519npk3swvcdNCuC7880dF0Ck1QzxVMKHMGKmOYD5sNkoefknSXfQYJvz7TekJ0xIAe2CjJwSf3PybLgXXd+BzpcHZHHQP8CiPAoOWfjVn0edxJKOE7mNLKPka4HNLfG467SOkayGpSCvjF3XWSNSfV9qpbU3W0BzJBxLmkxIXj4bdD/VaBjrFH5Mzxg7MkqcaOhwHwBuOR+V4GVuHNcU0oubMK032GNh+P5Wdjsq4nGet6vh/619OXj1qEUtQeI/EiswJISXFckArZhGMRxcUFvPhJJm4REmPusQnZ0OrHXIdCIKQVmdTrwYTee2xBStV7T/7PIvQZWY/mJWlcSb1oDrdtCZH3Y6HIzOpnjIFxh2E4eofGcvarrZkz8awZXkdIXd/J6rxFyH6jzJK9rsgkjNs5hac8mMy6RRgESDyEECU6OStzKuVk30lwezXQ3NvpNN8Pte2h/bVYeuEX1zYYyb2zVbssBDljMWA+jszCEJ80xw7hv57Hp9gTCLzcPpBboqYA7+xfiqsjtc25FbEWYaG6oFBdiiuQyR7SCgcJS9LCgOHGfxfYMJ6WB2/a/X4AxRnJ9tgir3oLNdpL1KJ59l2KsIIt8ijP7TWRixqcldbn0jDGXBNvPv4C3QjuTqveJGAAAAAASUVORK5CYII=') !important;
    }

    .references {
        margin-top: 2rem;
        font-size: 0.75rem;
        display: flex;
        justify-content: flex-start;
        flex-direction: column;
        align-items: center;
    }

    .references a {
        text-decoration: none;
        text-align: left;
    }
</style>

{{-- FilePond CSS for multiple image upload start --}}
<link rel="stylesheet" href="https://unpkg.com/filepond@4.30.4/dist/filepond.min.css">
<link rel="stylesheet"
    href="https://unpkg.com/filepond-plugin-image-preview@4.6.12/dist/filepond-plugin-image-preview.min.css">
{{-- FilePond CSS for multiple image upload end --}}

<!-- ai chat style include -->
@include('backend.partials.ai-chat-style')

@stack('styles')
