@php
    $translationsByLanguage = $translationsByLanguage ?? collect();
    $activeLanguageId = null;

    foreach ($languages as $index => $language) {
        if (
            $errors->has("translations.$index.language_id") ||
            $errors->has("translations.$index.question") ||
            $errors->has("translations.$index.answer")
        ) {
            $activeLanguageId = $language->id;
            break;
        }
    }

    if (!$activeLanguageId) {
        foreach ($languages as $index => $language) {
            $questionValue = old("translations.$index.question", $translationsByLanguage[$language->id]->question ?? '');
            $answerValue = old("translations.$index.answer", $translationsByLanguage[$language->id]->answer ?? '');

            if (trim((string) $questionValue) !== '' || trim(strip_tags((string) $answerValue)) !== '') {
                $activeLanguageId = $language->id;
                break;
            }
        }
    }

    if (!$activeLanguageId) {
        $activeLanguageId = $languages->first()?->id;
    }
@endphp

@error('translations')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="row g-4 faq-translation-layout">
    <div class="col-lg-3">
        <div class="card faq-language-sidebar">
            <div class="card-header">
                <h5 class="mb-0">Languages</h5>
            </div>
            <div class="card-body">
                <div class="nav flex-column nav-pills faq-language-nav" id="faq-language-tabs" role="tablist"
                    aria-orientation="vertical">
                    @foreach ($languages as $index => $language)
                        @php
                            $translation = $translationsByLanguage[$language->id] ?? null;
                            $questionValue = old("translations.$index.question", $translation?->question ?? '');
                            $answerValue = old("translations.$index.answer", $translation?->answer ?? '');
                            $hasQuestion = trim((string) $questionValue) !== '';
                            $hasAnswer = trim(strip_tags((string) $answerValue)) !== '';
                            $statusLabel = $hasQuestion && $hasAnswer ? 'Done' : (($hasQuestion || $hasAnswer) ? 'Draft' : 'Empty');
                            $statusClass = $hasQuestion && $hasAnswer ? 'success' : (($hasQuestion || $hasAnswer) ? 'warning' : 'secondary');
                            $code = strtolower($language->code);
                        @endphp

                        <button class="nav-link {{ (int) $activeLanguageId === (int) $language->id ? 'active' : '' }}"
                            id="faq-language-tab-{{ $language->id }}" data-bs-toggle="pill"
                            data-bs-target="#faq-language-pane-{{ $language->id }}" type="button" role="tab"
                            aria-controls="faq-language-pane-{{ $language->id }}"
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
        <div class="tab-content faq-language-content" id="faq-language-tab-content">
            @foreach ($languages as $index => $language)
                @php
                    $translation = $translationsByLanguage[$language->id] ?? null;
                    $code = strtolower($language->code);
                    $isRtl = in_array($code, ['ar', 'fa', 'ur', 'he']);
                @endphp

                <div class="tab-pane fade {{ (int) $activeLanguageId === (int) $language->id ? 'show active' : '' }}"
                    id="faq-language-pane-{{ $language->id }}" role="tabpanel"
                    aria-labelledby="faq-language-tab-{{ $language->id }}" tabindex="0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $language->name }}</h5>
                                <small class="text-muted">Translation content for {{ strtoupper($code) }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ $translation ? 'Saved before' : 'Optional' }}</span>
                        </div>
                        <div class="card-body faq-language-pane" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                            <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $language->id }}">

                            <div class="mb-3">
                                <label for="question_{{ $language->id }}" class="form-label">Question</label>
                                <input type="text" name="translations[{{ $index }}][question]"
                                    id="question_{{ $language->id }}" class="form-control"
                                    value="{{ old("translations.$index.question", $translation?->question) }}"
                                    placeholder="Enter question" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                                @error("translations.$index.question")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="answer_{{ $language->id }}" class="form-label">Answer</label>
                                <textarea name="translations[{{ $index }}][answer]" id="answer_{{ $language->id }}"
                                    class="form-control" rows="8" placeholder="Enter answer"
                                    dir="{{ $isRtl ? 'rtl' : 'ltr' }}">{{ old("translations.$index.answer", $translation?->answer) }}</textarea>
                                @error("translations.$index.answer")
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
