<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Faq;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Public API controller for FAQs and dynamic pages.
 *
 * Dynamic pages are translation-aware and can optionally resolve one preferred
 * language while still returning the full translations payload for clients that
 * need every language variant.
 */
class DynamicPageController extends Controller
{
    /**
     * Retrieve all active dynamic pages.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Optional language query narrows the resolved accessor values.
            [$languageCode, $languageId, $invalidLanguageResponse] = $this->resolveRequestedLanguage($request);

            if ($invalidLanguageResponse) {
                return $invalidLanguageResponse;
            }

            // Eager load translations so API serialization stays query-efficient.
            $dynamicPages = Page::with('translations.language')
                ->where('status', 'active')
                ->latest('id')
                ->get()
                ->each(fn(Page $page) => $page->useLanguage($languageCode, $languageId));

            return jsonResponse(
                true,
                'DynamicPage retrieved successfully',
                200,
                PageResource::collection($dynamicPages)->resolve()
            );
        } catch (\Exception $e) {
            return jsonErrorResponse(
                'Something went wrong while retrieving Dynamic Page',
                500,
                [$e->getMessage()]
            );
        }
    }

/**
 * Retrieve all active dynamic pages.
 */
    public function faq(Request $request): JsonResponse
    {
        try {
            // FAQ endpoint now follows the same language-resolution rules as dynamic pages.
            [$languageCode, $languageId, $invalidLanguageResponse] = $this->resolveRequestedLanguage($request);

            if ($invalidLanguageResponse) {
                return $invalidLanguageResponse;
            }

            $faqs = Faq::with('translations.language')
                ->where('status', 'active')
                ->latest('id')
                ->get()
                ->each(fn(Faq $faq) => $faq->useLanguage($languageCode, $languageId));

            return jsonResponse(
                true,
                'FAQ retrieved successfully',
                200,
                FaqResource::collection($faqs)->resolve()
            );
        } catch (\Exception $e) {
            return jsonErrorResponse(
                'Something went wrong while retrieving FAQ',
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * @return array{0:?string,1:?int,2:?JsonResponse}
     */
    private function resolveRequestedLanguage(Request $request): array
    {
        // Accept language filters like ?language=en or ?language=ar.
        $languageCode = strtolower(trim((string) $request->query('language', '')));

        if ($languageCode === '') {
            return [null, null, null];
        }

        $language = Language::query()
            ->whereRaw('LOWER(code) = ?', [$languageCode])
            ->first();

        // Return a validation-style API response when the requested language does not exist.
        if (! $language) {
            return [
                null,
                null,
                jsonErrorResponse('Validation failed.', 422, [
                    'language' => ['The selected language is invalid.'],
                ]),
            ];
        }

        return [$languageCode, $language->id, null];
    }
}
