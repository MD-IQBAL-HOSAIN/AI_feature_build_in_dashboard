<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Seed baseline dynamic pages with translation rows.
     *
     * Each entry below represents one logical page. The nested translations
     * array defines all language variants that should exist for that page.
     *
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resolve the languages used by the default seed content.
        $frenchLanguage = Language::query()->where('code', 'fr')->first();
        $defaultLanguage = Language::query()->where('code', 'en')->first()
            ?? Language::query()->where('status', 'active')->first();

        if (! $defaultLanguage) {
            $this->command?->warn('No language found for page seeding.');

            return;
        }

        $pages = [
            [
                'lookup_title' => 'About Us',
                'translations' => array_values(array_filter([
                    [
                        'language_id' => $defaultLanguage->id,
                        'page_title' => 'About Us',
                        'page_content' => 'This is the about us page',
                    ],
                    $frenchLanguage ? [
                        'language_id' => $frenchLanguage->id,
                        'page_title' => 'À propos de nous',
                        'page_content' => 'Cette page À propos de nous est en français.',
                    ] : null,
                ])),
            ],
            [
                'lookup_title' => 'Terms & Conditions',
                'translations' => array_values(array_filter([
                    [
                        'language_id' => $defaultLanguage->id,
                        'page_title' => 'Terms & Conditions',
                        'page_content' => 'This is the Terms & Conditions page',
                    ],
                    $frenchLanguage ? [
                        'language_id' => $frenchLanguage->id,
                        'page_title' => 'Conditions générales',
                        'page_content' => 'Cette page des conditions générales est en français.',
                    ] : null,
                ])),
            ],
            [
                'lookup_title' => 'Privacy Policy',
                'translations' => array_values(array_filter([
                    [
                        'language_id' => $defaultLanguage->id,
                        'page_title' => 'Privacy Policy',
                        'page_content' => 'This is the privacy policy page',
                    ],
                    $frenchLanguage ? [
                        'language_id' => $frenchLanguage->id,
                        'page_title' => 'Politique de confidentialité',
                        'page_content' => 'Cette page de politique de confidentialité est en français.',
                    ] : null,
                ])),
            ],
        ];

        foreach ($pages as $page) {
            // Match by the default language title so rerunning the seeder stays idempotent.
            $pageModel = Page::query()
                ->whereHas('translations', function ($query) use ($page, $defaultLanguage) {
                    $query->where('language_id', $defaultLanguage->id)
                        ->where('page_title', $page['lookup_title']);
                })
                ->first();

            $pageModel ??= Page::create([
                'status' => 'active',
            ]);

            foreach ($page['translations'] as $translation) {
                // Upsert each translation so the seeder can refresh demo content safely.
                $pageModel->translations()->updateOrCreate(
                    ['language_id' => $translation['language_id']],
                    [
                        'page_title' => $translation['page_title'],
                        'page_content' => $translation['page_content'],
                    ]
                );
            }
        }
    }
}
