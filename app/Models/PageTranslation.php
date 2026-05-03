<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Stores one language-specific version of a dynamic page.
 */
class PageTranslation extends Model
{
    protected $fillable = [
        'page_id',
        'language_id',
        'page_title',
        'page_content',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Parent logical page record shared by all translations.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Language assigned to this translation row.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
