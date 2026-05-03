@push('styles')
{{-- This css for faq create and edit both of forms --}}
    <style>
        .faq-language-sidebar {
            position: sticky;
            top: 110px;
        }

        .faq-language-nav {
            gap: 0.75rem;
        }

        .faq-language-nav .nav-link {
            align-items: center;
            border: 1px solid #e9ebec;
            border-radius: 0.75rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 1rem;
            text-align: left;
        }

        .faq-language-nav .nav-link.active {
            border-color: rgba(64, 81, 137, 0.25);
            box-shadow: 0 8px 20px rgba(64, 81, 137, 0.08);
        }

        .faq-language-content .card {
            min-height: 100%;
        }

        .faq-language-pane[dir="rtl"] .form-label,
        .faq-language-pane[dir="rtl"] .form-control {
            text-align: right;
        }

        @media (max-width: 991.98px) {
            .faq-language-sidebar {
                position: static;
            }
        }
    </style>
@endpush
