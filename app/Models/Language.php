<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    /**
     * Get the FAQs associated with the language.
     */
    public function faqs(): HasManyThrough
    {
        return $this->hasManyThrough(Faq::class, FaqTranslation::class, 'language_id', 'id', 'id', 'faq_id');
    }

    /**
     * Get logical pages that have a translation in this language.
     *
     * This goes through page_translations because page content is no longer
     * stored directly on the pages table.
     */
    public function pages(): HasManyThrough
    {
        return $this->hasManyThrough(Page::class, PageTranslation::class, 'language_id', 'id', 'id', 'page_id');
    }

    /**
     * Get the raw page translation rows for this language.
     */
    public function pageTranslations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    /**
     * Get the FAQ translations associated with the language.
     */
    public function faqTranslations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }
}
