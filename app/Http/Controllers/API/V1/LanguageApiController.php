<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\Api\V1\LanguageResource;
use App\Models\Language;
use Throwable;

class LanguageApiController extends BaseController
{
    /**
     * Return language list for onboarding and global app selections.
     */
    public function index()
    {
        try {
            // Return active languages in latest-first order.
            $languages = Language::query()
                ->select(['id', 'name', 'code'])
                ->where('status', 'active')
                ->latest()
                ->get();

            // Apply Resource formatting for consistent response shape.
            $formattedLanguages = LanguageResource::collection($languages)->resolve();

            return jsonResponse(true, 'Languages Retrieved successfully.', 200, $formattedLanguages);
        } catch (Throwable $e) {
            return jsonErrorResponse('Something went wrong while fetching languages.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}







