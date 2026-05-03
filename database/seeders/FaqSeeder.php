<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Language;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frenchLanguage = Language::query()->where('code', 'fr')->first();
        $defaultLanguage = Language::query()->where('code', 'en')->first()
            ?? Language::query()->where('status', 'active')->first();

        if (! $defaultLanguage) {
            $this->command?->warn('No language found for FAQ seeding.');

            return;
        }

        $faqs = [
            [
                'lookup_question' => 'What is FAQ?',
                'sort_order' => 0,
                'translations' => array_values(array_filter([
                    [
                        'language_id' => $defaultLanguage->id,
                        'question' => 'What is FAQ?',
                        'answer' => 'FAQ stands for Frequently Asked Questions, which are common questions and their answers.',
                    ],
                    $frenchLanguage ? [
                        'language_id' => $frenchLanguage->id,
                        'question' => 'Qu’est-ce qu’une FAQ ?',
                        'answer' => 'FAQ signifie Foire Aux Questions, qui regroupe les questions les plus courantes et leurs réponses.',
                    ] : null,
                ])),
            ],
        ];

        foreach ($faqs as $faq) {
            $faqModel = Faq::query()
                ->whereHas('translations', function ($query) use ($faq, $defaultLanguage) {
                    $query->where('language_id', $defaultLanguage->id)
                        ->where('question', $faq['lookup_question']);
                })
                ->first();

            $faqModel ??= Faq::create([
                'status' => 'active',
                'sort_order' => $faq['sort_order'],
            ]);

            $faqModel->update([
                'sort_order' => $faq['sort_order'],
            ]);

            foreach ($faq['translations'] as $translation) {
                $faqModel->translations()->updateOrCreate(
                    ['language_id' => $translation['language_id']],
                    [
                        'question' => $translation['question'],
                        'answer' => $translation['answer'],
                    ]
                );
            }
        }
    }
}
