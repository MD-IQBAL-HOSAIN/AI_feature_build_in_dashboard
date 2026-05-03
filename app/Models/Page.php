<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Base dynamic page entity.
 *
 * The page row stores shared page-level metadata only. All language-specific
 * content lives in the related page_translations table so one page ID can
 * represent the same logical page across multiple languages.
 */
class Page extends Model
{
    protected $fillable = [
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Cached language code used by the virtual accessors below.
     */
    protected ?string $resolvedLanguageCode = null;

    /**
     * Cached language id used by the virtual accessors below.
     */
    protected ?int $resolvedLanguageId = null;

    /**
     * All language variants that belong to the same logical page.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    /**
     * Store the preferred language context on the model instance so accessors
     * can resolve page_title/page_content from the matching translation.
     */
    public function useLanguage(?string $languageCode = null, ?int $languageId = null): static
    {
        $this->resolvedLanguageCode = $languageCode ? strtolower($languageCode) : null;
        $this->resolvedLanguageId = $languageId;

        return $this;
    }

    /**
     * Expose the resolved title as if it were a native page column.
     */
    public function getPageTitleAttribute(): ?string
    {
        return $this->resolveTranslation()?->page_title;
    }

    /**
     * Expose the resolved content as if it were a native page column.
     */
    public function getPageContentAttribute(): ?string
    {
        return $this->resolveTranslation()?->page_content;
    }

    /**
     * Expose the language id of the currently resolved translation.
     */
    public function getLanguageIdAttribute(): ?int
    {
        return $this->resolveTranslation()?->language_id;
    }

    /**
     * Expose the language model of the currently resolved translation.
     */
    public function getLanguageAttribute(): ?Language
    {
        return $this->resolveTranslation()?->language;
    }

    /**
     * Resolve the best translation for the requested language.
     *
     * Resolution order:
     * 1. Explicit language id
     * 2. Explicit language code
     * 3. English fallback
     * 4. First available translation
     */
    public function resolveTranslation(?string $languageCode = null, ?int $languageId = null): ?PageTranslation
    {
        $languageCode = $languageCode ? strtolower($languageCode) : $this->resolvedLanguageCode;
        $languageId = $languageId ?? $this->resolvedLanguageId;

        // Reuse eager-loaded translations when available to avoid extra queries.
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->with('language')->get();

        // Language id matching is the most precise filter, so it runs first.
        if ($languageId) {
            $translation = $translations->firstWhere('language_id', $languageId);
            if ($translation) {
                return $translation;
            }
        }

        // Language code matching supports API/frontend use-cases that pass "en"/"ar".
        if ($languageCode) {
            $translation = $translations->first(function (PageTranslation $translation) use ($languageCode) {
                return strtolower((string) $translation->language?->code) === $languageCode;
            });

            if ($translation) {
                return $translation;
            }
        }

        // English remains the default fallback when no explicit language matches.
        $fallback = $translations->first(function (PageTranslation $translation) {
            return strtolower((string) $translation->language?->code) === 'en';
        });

        // Final fallback keeps the page usable even when English is missing.
        return $fallback ?: $translations->first();
    }
}
