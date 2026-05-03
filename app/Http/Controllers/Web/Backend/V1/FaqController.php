<?php
namespace App\Http\Controllers\Web\Backend\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\FaqServiceInterface;
use App\Models\Language;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    public function __construct(private readonly FaqServiceInterface $faqService)
    {
    }

    /**
     * Display a listing of content.
     *
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            $data = $this->faqService->getAllLatest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('question', function ($data) {
                    $question = $data->question ?? 'N/A';
                    return $question;
                })
                ->addColumn('answer', function ($data) {
                    $answer = Str::limit(strip_tags($data->answer ?? 'N/A'), 30);
                    return $answer;
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

                            return $translation->language->name . ' (' . strtolower($translation->language->code) . ')';
                        })
                        ->filter()
                        ->implode(', ');
                })

                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })

                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="' . route('faq.edit', ['id' => $data->id]) . '" type="button" class="btn btn-primary fs-14 text-white edit-icn" title="Edit">
                                   <i class="mdi mdi-pencil"></i>
                                </a>
                                <a href="' . route('faq.show', ['id' => $data->id]) . '" type="button" class="btn btn-info fs-14 text-white edit-icn" title="Edit">
                                   <i class="mdi mdi-eye"></i>
                                </a>
                                 <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['question', 'answer', 'language', 'status', 'action'])
                ->make();
        }
        return view("backend.v1.faqs.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return view("backend.v1.faqs.create", compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status'                     => 'nullable|in:active,inactive',
                'sort_order'                 => 'nullable|integer|min:0',
                'translations'               => 'nullable|array|min:1',
                'translations.*.language_id' => 'nullable|exists:languages,id',
                'translations.*.question'    => 'nullable|string|max:500',
                'translations.*.answer'      => 'nullable|string|max:2000',
            ]);

            $validator->after(function (ValidationValidator $validator) use ($request): void {
                $this->validateTranslationRows($validator, $request->input('translations', []));
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $payload = $this->buildFaqPayload($validator->validated());

            if ($payload === null) {
                return redirect()->back()
                    ->withErrors([
                        'translations' => 'At least one complete language version is required.',
                    ])
                    ->withInput();
            }

            $this->faqService->create($payload);

            return redirect()->route('faq.index')->with('t-success', 'Created Successfully !!');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id): View
    {
        $data      = $this->faqService->findOrFail($id);
        $languages = Language::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
        $translationsByLanguage = $data->translations->keyBy('language_id');

        return view("backend.v1.faqs.edit", compact('data', 'languages', 'translationsByLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status'                     => 'nullable|in:active,inactive',
                'sort_order'                 => 'nullable|integer|min:0',
                'translations'               => 'nullable|array|min:1',
                'translations.*.language_id' => 'nullable|exists:languages,id',
                'translations.*.question'    => 'nullable|string|max:500',
                'translations.*.answer'      => 'nullable|string|max:2000',
            ]);

            $validator->after(function (ValidationValidator $validator) use ($request): void {
                $this->validateTranslationRows($validator, $request->input('translations', []));
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $payload = $this->buildFaqPayload($validator->validated());

            if ($payload === null) {
                return redirect()->back()
                    ->withErrors([
                        'translations' => 'At least one complete language version is required.',
                    ])
                    ->withInput();
            }

            $this->faqService->update($id, $payload);

            return redirect()->route('faq.index')->with('t-success', 'Updated Successfully!!');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public function show(int $id): View
    {
        $data = $this->faqService->findOrFail($id);
        return view('backend.v1.faqs.show', compact('data'));
    }

    /**
     * Update the status of the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse
    {
        $data = $this->faqService->toggleStatus($id);
        if ($data->status === 'inactive') {

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Published Successfully.',
            'data'    => $data,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->faqService->delete($id);

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
     * Normalize either the existing single-language form or a translations payload.
     *
     * @param array<string, mixed> $validated
     * @return array<string, mixed>|null
     */
    private function buildFaqPayload(array $validated): ?array
    {
        $payload = [];

        if (array_key_exists('status', $validated)) {
            $payload['status'] = $validated['status'];
        }

        if (array_key_exists('sort_order', $validated)) {
            $payload['sort_order'] = $validated['sort_order'];
        }

        if (! empty($validated['translations'])) {
            $translations = collect($validated['translations'])
                ->filter(fn($translation) => is_array($translation))
                ->map(function (array $translation): array {
                    return [
                        'language_id' => $translation['language_id'] ?? null,
                        'question'    => trim((string) ($translation['question'] ?? '')),
                        'answer'      => trim((string) ($translation['answer'] ?? '')),
                    ];
                })
                ->filter(function (array $translation): bool {
                    return $translation['question'] !== '' || $this->plainText($translation['answer']) !== '';
                })
                ->values()
                ->all();

            if ($translations === []) {
                return null;
            }

            $payload['translations'] = $translations;

            return $payload;
        }

        return null;
    }

    /**
     * Validate translation rows while allowing completely empty languages to be skipped.
     *
     * @param mixed $rows
     */
    private function validateTranslationRows(ValidationValidator $validator, mixed $rows): void
    {
        if (! is_array($rows) || $rows === []) {
            return;
        }

        $hasAtLeastOneTranslation = false;

        foreach ($rows as $index => $translation) {
            if (! is_array($translation)) {
                continue;
            }

            $question   = trim((string) ($translation['question'] ?? ''));
            $answer     = $this->plainText((string) ($translation['answer'] ?? ''));
            $languageId = $translation['language_id'] ?? null;

            if ($question === '' && $answer === '') {
                continue;
            }

            if (empty($languageId)) {
                $validator->errors()->add("translations.$index.language_id", 'Language is required for this translation.');
            }

            if ($question === '') {
                $validator->errors()->add("translations.$index.question", 'Question is required for this translation.');
            }

            if ($answer === '') {
                $validator->errors()->add("translations.$index.answer", 'Answer is required for this translation.');
            }

            if (! empty($languageId) && $question !== '' && $answer !== '') {
                $hasAtLeastOneTranslation = true;
            }
        }

        if (! $hasAtLeastOneTranslation) {
            $validator->errors()->add('translations', 'At least one complete language version is required.');
        }
    }

    private function plainText(?string $value): string
    {
        $text = html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);

        return trim($text);
    }
}
