@push('styles')
    <style>
        /* Keep the language switcher visible while the admin scrolls through long content fields. */
        .page-language-sidebar {
            position: sticky;
            top: 110px;
        }

        /* Add spacing between language pills for better scanability. */
        .page-language-nav {
            gap: 0.75rem;
        }

        /* Language buttons are designed to show label plus completion state. */
        .page-language-nav .nav-link {
            align-items: center;
            border: 1px solid #e9ebec;
            border-radius: 0.75rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 1rem;
            text-align: left;
        }

        .page-language-nav .nav-link.active {
            border-color: rgba(64, 81, 137, 0.25);
            box-shadow: 0 8px 20px rgba(64, 81, 137, 0.08);
        }

        /* Let the translation pane card stretch naturally with the editor content. */
        .page-language-content .card {
            min-height: 100%;
        }

        /* Non-CKEditor inputs inside RTL panes should align with the selected language direction. */
        .page-language-pane[dir="rtl"] .form-label,
        .page-language-pane[dir="rtl"] .form-control {
            text-align: right;
        }

        @media (max-width: 991.98px) {
            /* Disable sticky behavior on smaller screens to avoid layout friction. */
            .page-language-sidebar {
                position: static;
            }
        }
    </style>
@endpush
