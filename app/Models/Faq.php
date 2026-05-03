<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends Model
{
    protected $fillable = [
        'status',
        'sort_order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected ?string $resolvedLanguageCode = null;

    protected ?int $resolvedLanguageId = null;

    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function useLanguage(?string $languageCode = null, ?int $languageId = null): static
    {
        $this->resolvedLanguageCode = $languageCode ? strtolower($languageCode) : null;
        $this->resolvedLanguageId = $languageId;

        return $this;
    }

    public function getQuestionAttribute(): ?string
    {
        return $this->resolveTranslation()?->question;
    }

    public function getAnswerAttribute(): ?string
    {
        return $this->resolveTranslation()?->answer;
    }

    public function getLanguageIdAttribute(): ?int
    {
        return $this->resolveTranslation()?->language_id;
    }

    public function getLanguageAttribute(): ?Language
    {
        return $this->resolveTranslation()?->language;
    }

    public function resolveTranslation(?string $languageCode = null, ?int $languageId = null): ?FaqTranslation
    {
        $languageCode = $languageCode ? strtolower($languageCode) : $this->resolvedLanguageCode;
        $languageId = $languageId ?? $this->resolvedLanguageId;

        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->with('language')->get();

        if ($languageId) {
            $translation = $translations->firstWhere('language_id', $languageId);
            if ($translation) {
                return $translation;
            }
        }

        if ($languageCode) {
            $translation = $translations->first(function (FaqTranslation $translation) use ($languageCode) {
                return strtolower((string) $translation->language?->code) === $languageCode;
            });

            if ($translation) {
                return $translation;
            }
        }

        $fallback = $translations->first(function (FaqTranslation $translation) {
            return strtolower((string) $translation->language?->code) === 'en';
        });

        return $fallback ?: $translations->first();
    }
}
