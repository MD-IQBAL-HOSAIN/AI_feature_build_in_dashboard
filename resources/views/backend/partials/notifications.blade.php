
@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif

@if (@$errors->any())
<script>
    @foreach ($errors->all() as $error)
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ $error }}',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    @endforeach
</script>
@endif

<script>
    (function () {
        var html = document.documentElement;

        // Read persisted mode from either storage to support template defaults and custom persistence.
        function getSavedMode() {
            var mode = localStorage.getItem("layout-mode") || sessionStorage.getItem("data-layout-mode");
            return mode === "dark" || mode === "light" ? mode : null;
        }

        // Apply mode and keep both storage layers in sync for reliable reload behavior.
        function applyMode(mode) {
            html.setAttribute("data-layout-mode", mode);
            localStorage.setItem("layout-mode", mode);
            sessionStorage.setItem("data-layout-mode", mode);
        }

        // Mirror DOM changes made by the template script (app.js) back to localStorage.
        function syncModeFromDom() {
            var mode = html.getAttribute("data-layout-mode");
            if (mode === "dark" || mode === "light") {
                localStorage.setItem("layout-mode", mode);
                sessionStorage.setItem("data-layout-mode", mode);
            }
        }

        var savedMode = getSavedMode();
        if (savedMode) {
            applyMode(savedMode);
        }

        document.addEventListener("DOMContentLoaded", function () {
            syncModeFromDom();

            // Do not attach a manual click toggle here; app.js already handles switch interaction.
            if (typeof MutationObserver !== "undefined") {
                new MutationObserver(function (mutations) {
                    for (var i = 0; i < mutations.length; i++) {
                        if (mutations[i].type === "attributes" && mutations[i].attributeName === "data-layout-mode") {
                            syncModeFromDom();
                            break;
                        }
                    }
                }).observe(html, {
                    attributes: true,
                    attributeFilter: ["data-layout-mode"]
                });
            }
        });
    })();
</script>
