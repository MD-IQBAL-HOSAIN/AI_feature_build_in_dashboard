@php
    // Reuse saved translations keyed by language so each tab can read its own data quickly.
    $translationsByLanguage = $translationsByLanguage ?? collect();
    $activeLanguageId = null;

    // If validation fails, open the tab that contains the first translation error.
    foreach ($languages as $index => $language) {
        if (
            $errors->has("translations.$index.language_id") ||
            $errors->has("translations.$index.page_title") ||
            $errors->has("translations.$index.page_content")
        ) {
            $activeLanguageId = $language->id;
            break;
        }
    }

    // Otherwise, open the first tab that already has content so the admin lands on meaningful data.
    if (!$activeLanguageId) {
        foreach ($languages as $index => $language) {
            $pageTitleValue = old("translations.$index.page_title", $translationsByLanguage[$language->id]->page_title ?? '');
            $pageContentValue = old("translations.$index.page_content", $translationsByLanguage[$language->id]->page_content ?? '');

            if (trim((string) $pageTitleValue) !== '' || trim(strip_tags((string) $pageContentValue)) !== '') {
                $activeLanguageId = $language->id;
                break;
            }
        }
    }

    if (!$activeLanguageId) {
        // Fresh create screens default to the first available language tab.
        $activeLanguageId = $languages->first()?->id;
    }
@endphp

{{-- Cross-language validation errors that apply to the whole translation set. --}}
@error('translations')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="row g-4 page-translation-layout">
    <div class="col-lg-3">
        {{-- Sidebar listing all languages and their completion state. --}}
        <div class="card page-language-sidebar">
            <div class="card-header">
                <h5 class="mb-0">Languages</h5>
            </div>
            <div class="card-body">
                <div class="nav flex-column nav-pills page-language-nav" id="page-language-tabs" role="tablist"
                    aria-orientation="vertical">
                    @foreach ($languages as $index => $language)
                        @php
                            // Use old input first so validation errors preserve the admin's unsaved work.
                            $translation = $translationsByLanguage[$language->id] ?? null;
                            $pageTitleValue = old("translations.$index.page_title", $translation?->page_title ?? '');
                            $pageContentValue = old("translations.$index.page_content", $translation?->page_content ?? '');
                            $hasTitle = trim((string) $pageTitleValue) !== '';
                            $hasContent = trim(strip_tags((string) $pageContentValue)) !== '';
                            $statusLabel = $hasTitle && $hasContent ? 'Done' : (($hasTitle || $hasContent) ? 'Draft' : 'Empty');
                            $statusClass = $hasTitle && $hasContent ? 'success' : (($hasTitle || $hasContent) ? 'warning' : 'secondary');
                            $code = strtolower($language->code);
                        @endphp

                        <button class="nav-link {{ (int) $activeLanguageId === (int) $language->id ? 'active' : '' }}"
                            id="page-language-tab-{{ $language->id }}" data-bs-toggle="pill"
                            data-bs-target="#page-language-pane-{{ $language->id }}" type="button" role="tab"
                            aria-controls="page-language-pane-{{ $language->id }}"
                            aria-selected="{{ (int) $activeLanguageId === (int) $language->id ? 'true' : 'false' }}">
                            <span>
                                <strong>{{ $language->name }}</strong>
                                <small class="d-block text-muted">{{ $code }}</small>
                            </span>
                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        {{-- Content panes render one page translation form per language. --}}
        <div class="tab-content page-language-content" id="page-language-tab-content">
            @foreach ($languages as $index => $language)
                @php
                    $translation = $translationsByLanguage[$language->id] ?? null;
                    $code = strtolower($language->code);
                    $isRtl = in_array($code, ['ar', 'fa', 'ur', 'he']);
                @endphp

                <div class="tab-pane fade {{ (int) $activeLanguageId === (int) $language->id ? 'show active' : '' }}"
                    id="page-language-pane-{{ $language->id }}" role="tabpanel"
                    aria-labelledby="page-language-tab-{{ $language->id }}" tabindex="0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $language->name }}</h5>
                                <small class="text-muted">Translation content for {{ strtoupper($code) }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ $translation ? 'Saved before' : 'Optional' }}</span>
                        </div>
                        <div class="card-body page-language-pane" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                            {{-- Hidden language id keeps the submitted payload aligned with the selected tab. --}}
                            <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $language->id }}">

                            <div class="mb-3">
                                <label for="page_title_{{ $language->id }}" class="form-label">Page Title</label>
                                {{-- Plain input for the language-specific page title. --}}
                                <input type="text" name="translations[{{ $index }}][page_title]"
                                    id="page_title_{{ $language->id }}" class="form-control"
                                    value="{{ old("translations.$index.page_title", $translation?->page_title) }}"
                                    placeholder="Enter page title">
                                @error("translations.$index.page_title")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="page_content_{{ $language->id }}" class="form-label">Page Content</label>
                                {{-- CKEditor field for rich-text page content per language. --}}
                                <textarea name="translations[{{ $index }}][page_content]" id="page_content_{{ $language->id }}"
                                    class="form-control ck-editor" rows="8" placeholder="Enter page content">{{ old("translations.$index.page_content", $translation?->page_content) }}</textarea>
                                @error("translations.$index.page_content")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
