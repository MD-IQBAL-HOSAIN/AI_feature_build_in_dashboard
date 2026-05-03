<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the translation table that stores all language-specific page data.
     *
     * The parent pages table remains language-agnostic so one page ID can own
     * multiple localized content rows.
     *
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_translations', function (Blueprint $table) {
            // Translation rows are isolated per page/language pair.
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('page_title');
            $table->longText('page_content');
            $table->timestamps();

            // Prevent duplicate translations for the same language on one page.
            $table->unique(['page_id', 'language_id']);
        });
    }

    /**
     * Drop the translation table.
     *
     * Rolling back removes only localized content because the logical page rows
     * continue to live in the pages table.
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_translations');
    }
};
