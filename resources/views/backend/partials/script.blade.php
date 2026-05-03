<!-- JAVASCRIPT -->
<!-- jQuery (required) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
{{-- <script src="{{ asset('backend/plugins/select2/js/select2.min.js') }}"></script> --}}

<!-- Dropify JS -->
<script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>

<!--Swiper sweet alert 2 js-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<!-- apexcharts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Vector map-->
<script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!--Swiper slider js-->
<script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- Dashboard init -->
<script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>


<!-- DataTables with Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Sidebar scroll containment -->
<script>
    (function() {
        var sidebar = document.querySelector('.app-menu.navbar-menu');
        if (!sidebar) return;

        sidebar.addEventListener('wheel', function(e) {
            // Find the actual scrollable element (SimpleBar wrapper or the sidebar itself)
            var scrollEl = sidebar.querySelector('.simplebar-content-wrapper');
            var useEl = scrollEl || sidebar;
            var computedOverflow = window.getComputedStyle(useEl).overflowY;

            // If SimpleBar overflow is set to 'visible' (collapsed state), use #scrollbar directly
            if (computedOverflow === 'visible') {
                useEl = document.getElementById('scrollbar') || sidebar;
            }

            var scrollTop = useEl.scrollTop;
            var scrollHeight = useEl.scrollHeight;
            var clientHeight = useEl.clientHeight;

            // Manually scroll and prevent event from reaching page-content
            if (scrollHeight > clientHeight) {
                useEl.scrollTop += e.deltaY;
                e.preventDefault();
                e.stopPropagation();
            } else {
                // Even if nothing to scroll, don't let it reach page-content
                e.preventDefault();
                e.stopPropagation();
            }
        }, {
            passive: false
        });
    })();
</script>

<script>
    $(document).ready(function() {
        // Initialize Dropify

        // Optional events
        let drEvent = $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove file',
                'error': 'Oops, something wrong happened.'
            }
        });

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });
    });
</script>


<script>
    $(document).ready(function() {
        const logoutBtn = document.getElementById('logout-button');
        logoutBtn.style.cursor = "pointer";

        $('#logout-button').on('click', function() {
            fetch("{{ route('auth.logout.post') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "Accept": 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    });
</script>



{{-- INTERNAL Summernote Editor js --}}
<script src="{{ asset('backend/plugins/summernote-editor/summernote1.js') }}"></script>
<script src="{{ asset('backend/js/summernote.js') }}"></script>
{{-- dropify js --}}
<script src="{{ asset('backend/js/dropify.min.js') }}"></script>

{{-- toaster js --}}
<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
{{-- toastr start --}}
<script>
    $(document).ready(function() {
        toastr.options.timeOut = 10000;
        toastr.options.positionClass = 'toast-top-right';

        @if (Session::has('t-success'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.success("{{ session('t-success') }}");
        @endif

        @if (Session::has('t-error'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.error("{{ session('t-error') }}");
        @endif

        @if (Session::has('t-info'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.info("{{ session('t-info') }}");
        @endif

        @if (Session::has('t-warning'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.warning("{{ session('t-warning') }}");
        @endif
    });
</script>
{{-- toastr end --}}

{{-- dropify start --}}
<script>
    $(document).ready(function() {
        $('.dropify').dropify();

        $('#logo').on('dropify.afterClear', function(event, element) {
            $('input[name="remove_logo"]').val('1');
        });

        $('#favicon').on('dropify.afterClear', function(event, element) {
            $('input[name="remove_favicon"]').val('1');
        });
    });
</script>
{{-- dropify end --}}

{{-- summernot start --}}
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            tabsize: 2,
            height: 220,
        });
    });
</script>
{{-- summetnote end --}}

{{-- ck editor start --}}
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Resolve direction from the closest pane first. This is required for
        // multilingual tab layouts such as Dynamic Page where each language tab
        // has its own fixed direction and no language select exists inside the form.
        function getDirectionConfig(textareaElement) {
            const directionContainer = textareaElement.closest('[dir]');
            if (directionContainer) {
                const direction = directionContainer.getAttribute('dir') === 'rtl' ? 'rtl' : 'ltr';

                return {
                    direction,
                    align: direction === 'rtl' ? 'right' : 'left',
                };
            }

            const formElement = textareaElement.closest('form');
            if (!formElement) return null;

            const languageSelect = formElement.querySelector('.js-language-direction');
            if (!languageSelect) return null;

            const selectedOption = languageSelect.options[languageSelect.selectedIndex];
            const selectedCode = (selectedOption && selectedOption.dataset && selectedOption.dataset.code) ?
                selectedOption.dataset.code.toLowerCase() :
                '';
            const isRtl = selectedCode === 'ar';

            return {
                direction: isRtl ? 'rtl' : 'ltr',
                align: isRtl ? 'right' : 'left',
            };
        }

        // Keep CKEditor-generated block elements aligned with the active language direction.
        function syncCkContentBlocks(editableElement, direction, align) {
            editableElement.querySelectorAll('p, li, h1, h2, h3, h4, h5, h6, blockquote').forEach((node) => {
                node.setAttribute('dir', direction);
                node.style.textAlign = align;
            });
        }

        function applyCkEditorDirection(textareaElement, editorInstance) {
            const directionConfig = getDirectionConfig(textareaElement);
            if (!directionConfig) return;

            const editableElement = editorInstance.ui.view.editable.element;
            const direction = directionConfig.direction;
            const align = directionConfig.align;

            // Update the editable root immediately so the caret starts from the
            // correct side for the selected language.
            editableElement.setAttribute('dir', direction);
            editableElement.style.direction = direction;
            editableElement.style.textAlign = align;

            // Mirror the same direction on the internal CKEditor editing root.
            editorInstance.editing.view.change((writer) => {
                const root = editorInstance.editing.view.document.getRoot();
                writer.setAttribute('dir', direction, root);
                writer.setStyle('direction', direction, root);
                writer.setStyle('text-align', align, root);
            });

            syncCkContentBlocks(editableElement, direction, align);

            // New paragraphs are created after the editor is initialized, so
            // keep reapplying direction on every content change.
            editorInstance.model.document.on('change:data', () => {
                editableElement.setAttribute('dir', direction);
                editableElement.style.direction = direction;
                editableElement.style.textAlign = align;
                syncCkContentBlocks(editableElement, direction, align);
            });
        }

        document.querySelectorAll('.ck-editor').forEach((element) => {
            const directionConfig = getDirectionConfig(element);
            const contentLanguage = directionConfig?.direction === 'rtl' ? 'ar' : 'en';

            ClassicEditor
                .create(element, {
                    language: {
                        content: contentLanguage
                    },
                    removePlugins: [
                        'Image',
                        'ImageToolbar',
                        'ImageUpload',
                        'EasyImage',
                        'MediaEmbed',
                        'CKFinder',
                        'CKFinderUploadAdapter',
                        'CloudServices'
                    ],
                    height: '500px',
                    toolbar: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'undo',
                        'redo'
                    ]
                })
                .then((editor) => {
                    applyCkEditorDirection(element, editor);
                })
                .catch((error) => {
                    console.error(error);
                });
        });
    });
</script>
{{-- ck editor end --}}

{{-- FilePond js for multiple image upload start --}}
<script src="https://unpkg.com/filepond-plugin-image-preview@4.6.12/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond@4.30.4/dist/filepond.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof FilePond === 'undefined') return;

        FilePond.registerPlugin(FilePondPluginImagePreview);

        const loadFromUrl = (source, load, error, progress, abort) => {
            progress(true, 0, 1);
            fetch(source)
                .then((res) => (res.ok ? res.blob() : Promise.reject()))
                .then((blob) => (progress(true, 1, 1), load(blob)))
                .catch(() => error('Could not load file'));
            return {
                abort: () => abort()
            };
        };

        const base = {
            storeAsFile: true,
            allowProcess: false,
            credits: false
        };

        document.querySelectorAll('.filepond').forEach((el) => {
            FilePond.create(el, {
                ...base,
                allowMultiple: true,
                // maxFiles: 4,
            });
        });
        //use this class for single image upload with preview and existing image display
        document.querySelectorAll('.filepond-single').forEach((el) => {
            const defaultFile = el.getAttribute('data-default-file');
            FilePond.create(el, {
                ...base,
                allowMultiple: false,
                maxFiles: 1,
                server: {
                    load: loadFromUrl
                },
                files: defaultFile ? [{
                    source: defaultFile,
                    options: {
                        type: 'local'
                    }
                }] : []
            });
        });
    });
</script>
{{-- FilePond js for multiple image upload end --}}

{{-- Global reusable language direction helper --}}
<script>
    (function() {
        function normalizeCodes(codes) {
            return String(codes || 'ar')
                .split(',')
                .map(function(code) {
                    return code.trim().toLowerCase();
                })
                .filter(Boolean);
        }

        function applyDirection(selectElement) {
            if (!selectElement) return;

            var targetSelector = selectElement.getAttribute('data-direction-target');
            var targetElement = targetSelector ? document.querySelector(targetSelector) : selectElement.closest(
                'form');
            if (!targetElement) return;

            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var selectedCode = (selectedOption && selectedOption.dataset && selectedOption.dataset.code) ?
                selectedOption.dataset.code.toLowerCase() : '';
            var rtlCodes = normalizeCodes(selectElement.getAttribute('data-rtl-codes'));
            var isRtl = rtlCodes.includes(selectedCode);
            var direction = isRtl ? 'rtl' : 'ltr';
            var align = isRtl ? 'right' : 'left';

            targetElement.setAttribute('dir', direction);
            targetElement.classList.toggle('is-rtl-language', isRtl);

            targetElement.querySelectorAll(
                'input[type="text"], input[type="search"], input[type="email"], textarea').forEach(function(
                field) {
                field.setAttribute('dir', direction);
                field.style.textAlign = align;
            });

            // Keep CKEditor editable UI in sync with selected language direction.
            targetElement.querySelectorAll('.ck-editor__editable').forEach(function(field) {
                field.setAttribute('dir', direction);
                field.style.textAlign = align;

                field.querySelectorAll('p, li, h1, h2, h3, h4, h5, h6, blockquote').forEach(function(node) {
                    node.setAttribute('dir', direction);
                    node.style.textAlign = align;
                });
            });
        }

        document.addEventListener('change', function(event) {
            if (event.target && event.target.classList.contains('js-language-direction')) {
                applyDirection(event.target);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-language-direction').forEach(function(element) {
                applyDirection(element);
            });
        });

        window.LanguageDirectionHelper = {
            apply: applyDirection,
            init: function(selector) {
                document.querySelectorAll(selector || '.js-language-direction').forEach(function(element) {
                    applyDirection(element);
                });
            }
        };
    })();
</script>

<!-- ai chat script include -->
@include('backend.partials.ai-chat-script')

@stack('scripts')
