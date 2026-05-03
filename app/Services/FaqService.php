<?php

namespace App\Services;

use App\Interfaces\FaqServiceInterface;
use App\Models\Faq;
use Illuminate\Support\Collection;

/**
 * Default FAQ service implementation.
 *
 * This class encapsulates FAQ persistence operations and keeps controllers
 * focused on HTTP concerns.
 */
class FaqService implements FaqServiceInterface
{
    /**
     * Retrieve all FAQ records in descending creation order.
     *
     * @return Collection<int, Faq>
     */
    public function getAllLatest(): Collection
    {
        return Faq::with('translations.language')
            ->orderBy('sort_order')
            ->latest('id')
            ->get();
    }

    /**
     * Retrieve a FAQ by ID or fail if it does not exist.
     *
     * @param int $id FAQ identifier.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Faq
    {
        return Faq::with('translations.language')->findOrFail($id);
    }

    /**
     * Create and persist a new FAQ record.
     *
     * @param array<string, mixed> $data Validated payload.
     * @return Faq
     */
    public function create(array $data): Faq
    {
        $faq = Faq::create([
            'status' => $data['status'] ?? 'active',
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $translations = $this->normalizeTranslations($data);

        if ($translations !== []) {
            $faq->translations()->createMany($translations);
        }

        return $faq->load('translations.language');
    }

    /**
     * Update a FAQ record with provided fields.
     *
     * @param int $id FAQ identifier.
     * @param array<string, mixed> $data Validated payload.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $id, array $data): Faq
    {
        $faq = $this->findOrFail($id);

        if (array_key_exists('status', $data)) {
            $faq->status = $data['status'];
        }

        if (array_key_exists('sort_order', $data)) {
            $faq->sort_order = $data['sort_order'];
        }

        $faq->save();

        foreach ($this->normalizeTranslations($data) as $translation) {
            $faq->translations()->updateOrCreate(
                ['language_id' => $translation['language_id']],
                [
                    'question' => $translation['question'],
                    'answer' => $translation['answer'],
                ]
            );
        }

        return $faq->load('translations.language');
    }

    /**
     * Toggle the FAQ status between "active" and "inactive".
     *
     * @param int $id FAQ identifier.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function toggleStatus(int $id): Faq
    {
        $faq = $this->findOrFail($id);
        $faq->status = $faq->status === 'active' ? 'inactive' : 'active';
        $faq->save();

        return $faq;
    }

    /**
     * Delete a FAQ record by ID.
     *
     * @param int $id FAQ identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }

    /**
     * Normalize either a single-language form submission or a multi-language payload.
     *
     * @param array<string, mixed> $data
     * @return array<int, array{language_id:int, question:string, answer:string}>
     */
    private function normalizeTranslations(array $data): array
    {
        if (!empty($data['translations']) && is_array($data['translations'])) {
            return collect($data['translations'])
                ->filter(fn ($translation) => is_array($translation))
                ->map(function (array $translation): array {
                    return [
                        'language_id' => (int) $translation['language_id'],
                        'question' => trim((string) $translation['question']),
                        'answer' => trim((string) $translation['answer']),
                    ];
                })
                ->filter(function (array $translation): bool {
                    return $translation['language_id'] > 0
                        && $translation['question'] !== ''
                        && $this->plainText($translation['answer']) !== '';
                })
                ->values()
                ->all();
        }

        return [];
    }

    private function plainText(?string $value): string
    {
        $text = html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);

        return trim($text);
    }
}
