<?php

namespace App\Http\Controllers\Web\Backend\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\DynamicPageServiceInterface;
use App\Models\Language;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Backend CRUD controller for multilingual dynamic pages.
 *
 * This controller keeps HTTP concerns here and delegates persistence details to
 * the DynamicPageServiceInterface implementation.
 */
class DynamicPageController extends Controller
{
    public function __construct(private readonly DynamicPageServiceInterface $dynamicPageService) {}

    /**
     * Display a listing of the resource.
     *
     * @return View|JsonResponse
     *
     * @SuppressWarnings("unused")
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            // DataTables requests consume the translation-aware page listing.
            $data = $this->dynamicPageService->getAllLatest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('page_title', function ($data) {
                    // The model accessor resolves the best translation automatically.
                    return $data->page_title ?? 'N/A';
                })
                ->addColumn('page_content', function ($data) {
                    // Keep the table concise while still exposing a content preview.
                    return Str::limit(strip_tags($data->page_content ?? 'N/A'), 60);
                })
                ->addColumn('language', function ($data) {
                    if ($data->translations->isEmpty()) {
                        return 'N/A';
                    }

                    return $data->translations
                        ->map(function ($translation) {
                            if (! $translation->language) {
                                return null;
                            }

                            // Display all available languages so admins can confirm coverage at a glance.
                            return $translation->language->name.' ('.strtolower($translation->language->code).')';
                        })
                        ->filter()
                        ->implode(', ');
                })

                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == 'active' ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == 'active' ? '26px' : '2px';

                    // Reuse the existing shared status toggle helper markup.
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })

                ->addColumn('action', function ($data) {
                    // Edit/show/delete remain page-level actions because one ID owns all translations.
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="'.route('dynamic.edit', ['id' => $data->id]).'" type="button" class="btn btn-primary fs-14 text-white edit-icn" title="Edit">
                                     <i class="mdi mdi-pencil"></i>
                                </a>
                                 <a href="'.route('dynamic.show', ['id' => $data->id]).'" type="button" class="btn btn-warning fs-14 text-white edit-icn" title="show">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                 <a href="#" type="button" onclick="showDeleteConfirm('.$data->id.')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </div>';
                })

                ->rawColumns(['page_title', 'page_content', 'language', 'status', 'action'])
                ->make();
        }

        return view('backend.v1.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|JsonResponse
     */
    public function create(): View
    {
        // Only active languages are offered in the admin form.
        $languages = Language::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return view('backend.v1.pages.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate each language tab independently while still allowing empty tabs.
            $validator = Validator::make($request->all(), [
                'translations' => 'nullable|array|min:1',
                'translations.*.language_id' => 'nullable|exists:languages,id',
                'translations.*.page_title' => 'nullable|string|max:1000',
                'translations.*.page_content' => 'nullable|string|max:50000',
            ]);

            $validator->after(function ($validator) use ($request): void {
                $this->validateTranslationRows($validator, $request->input('translations', []));
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Convert the request into the service-friendly translation payload.
            $payload = $this->buildPagePayload($validator->validated());

            if ($payload === null) {
                return redirect()->back()
                    ->withErrors([
                        'translations' => 'At least one complete language version is required.',
                    ])
                    ->withInput();
            }

            $this->dynamicPageService->create($payload);

            return redirect()->route('dynamic.index')->with('t-success', 'Created Successfully !!');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!'.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     *  @return View|JsonResponse
     */
    public function edit(int $id): View
    {
        $data = $this->dynamicPageService->findOrFail($id);

        // Active languages drive the tabs, while the keyed collection injects saved values per language.
        $languages = Language::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
        $translationsByLanguage = $data->translations->keyBy('language_id');

        return view('backend.v1.pages.edit', compact('data', 'languages', 'translationsByLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            // Update uses the same translation rules as create to keep behavior consistent.
            $validator = Validator::make($request->all(), [
                'translations' => 'nullable|array|min:1',
                'translations.*.language_id' => 'nullable|exists:languages,id',
                'translations.*.page_title' => 'nullable|string|max:1000',
                'translations.*.page_content' => 'nullable|string|max:50000',
            ]);

            $validator->after(function ($validator) use ($request): void {
                $this->validateTranslationRows($validator, $request->input('translations', []));
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Build a normalized payload so the service can upsert language rows cleanly.
            $payload = $this->buildPagePayload($validator->validated());

            if ($payload === null) {
                return redirect()->back()
                    ->withErrors([
                        'translations' => 'At least one complete language version is required.',
                    ])
                    ->withInput();
            }

            $this->dynamicPageService->update($id, $payload);

            return redirect()->route('dynamic.index')->with('t-success', 'Updated Successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!'.$e->getMessage());
        }
    }

    /**
     * Update the status of the specified dynamic page.
     */
    public function status(int $id): JsonResponse
    {
        // Status is a page-level flag, so toggling here affects every translation.
        $data = $this->dynamicPageService->toggleStatus($id);
        if ($data->status === 'inactive') {

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Published Successfully.',
            'data' => $data,
        ]);
    }

    /**
     * This function is used to show the details of a dynamic page.
     *
     * @param  int  $id  The ID of the dynamic page to show.
     */
    public function show(int $id): View
    {
        $data = $this->dynamicPageService->findOrFail($id);

        return view('backend.v1.pages.show', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     *  @return View|JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Deleting the parent page cascades to all translations.
            $this->dynamicPageService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Data.',
            ]);
        }
    }

    /**
     * Normalize the translations payload for service consumption.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>|null
     */
    private function buildPagePayload(array $validated): ?array
    {
        if (! empty($validated['translations'])) {
            $translations = collect($validated['translations'])
                ->filter(fn ($translation) => is_array($translation))
                ->map(function (array $translation): array {
                    // Keep payload values trimmed so service-level empty checks are reliable.
                    return [
                        'language_id' => $translation['language_id'] ?? null,
                        'page_title' => trim((string) ($translation['page_title'] ?? '')),
                        'page_content' => trim((string) ($translation['page_content'] ?? '')),
                    ];
                })
                ->filter(function (array $translation): bool {
                    // Preserve only rows where the admin started entering actual translation data.
                    return $translation['page_title'] !== '' || $this->plainText($translation['page_content']) !== '';
                })
                ->values()
                ->all();

            if ($translations === []) {
                return null;
            }

            return [
                'translations' => $translations,
            ];
        }

        return null;
    }

    /**
     * Validate translation rows while allowing fully empty tabs to be skipped.
     */
    private function validateTranslationRows(\Illuminate\Contracts\Validation\Validator $validator, mixed $rows): void
    {
        if (! is_array($rows) || $rows === []) {
            return;
        }

        // Require at least one fully completed translation before saving.
        $hasAtLeastOneTranslation = false;

        foreach ($rows as $index => $translation) {
            if (! is_array($translation)) {
                continue;
            }

            $pageTitle = trim((string) ($translation['page_title'] ?? ''));
            $pageContent = $this->plainText((string) ($translation['page_content'] ?? ''));
            $languageId = $translation['language_id'] ?? null;

            // Completely empty tabs are intentionally ignored.
            if ($pageTitle === '' && $pageContent === '') {
                continue;
            }

            if (empty($languageId)) {
                $validator->errors()->add("translations.$index.language_id", 'Language is required for this translation.');
            }

            if ($pageTitle === '') {
                $validator->errors()->add("translations.$index.page_title", 'Page title is required for this translation.');
            }

            if ($pageContent === '') {
                $validator->errors()->add("translations.$index.page_content", 'Page content is required for this translation.');
            }

            if (! empty($languageId) && $pageTitle !== '' && $pageContent !== '') {
                $hasAtLeastOneTranslation = true;
            }
        }

        if (! $hasAtLeastOneTranslation) {
            $validator->errors()->add('translations', 'At least one complete language version is required.');
        }
    }

    private function plainText(?string $value): string
    {
        // Convert HTML editor content to plain text so whitespace-only markup
        // does not count as meaningful page content.
        $text = html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);

        return trim($text);
    }
}
