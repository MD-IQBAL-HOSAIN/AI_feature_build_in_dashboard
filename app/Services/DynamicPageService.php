<?php

namespace App\Services;

use App\Interfaces\DynamicPageServiceInterface;
use App\Models\Page;
use Illuminate\Support\Collection;

/**
 * Default implementation for dynamic page operations.
 */
class DynamicPageService implements DynamicPageServiceInterface
{
    /**
     * Retrieve all dynamic pages ordered by latest first.
     *
     * @return Collection<int, Page>
     */
    public function getAllLatest(): Collection
    {
        // Always eager load translations so list/detail screens can read
        // language-specific values without triggering N+1 queries.
        return Page::with('translations.language')->latest('id')->get();
    }

    /**
     * Retrieve one dynamic page by ID.
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Page
    {
        // Detail/edit flows need all translations immediately.
        return Page::with('translations.language')->findOrFail($id);
    }

    /**
     * Create and persist a new dynamic page.
     *
     * @param  array<string, mixed>  $data  Validated payload.
     */
    public function create(array $data): Page
    {
        // Create the logical page shell first. Translation records are attached after.
        $page = Page::create([
            'status' => $data['status'] ?? 'active',
        ]);

        // Normalize and filter translation rows before persisting them.
        $translations = $this->normalizeTranslations($data);

        if ($translations !== []) {
            $page->translations()->createMany($translations);
        }

        return $page->load('translations.language');
    }

    /**
     * Update an existing dynamic page.
     *
     * @param  int  $id  Dynamic page identifier.
     * @param  array<string, mixed>  $data  Validated payload.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $id, array $data): Page
    {
        $page = $this->findOrFail($id);

        // Status remains on the parent page because it applies to all languages.
        if (array_key_exists('status', $data)) {
            $page->status = $data['status'];
        }

        $page->save();

        // Each language is upserted independently so partial edits do not delete
        // other saved translations.
        foreach ($this->normalizeTranslations($data) as $translation) {
            $page->translations()->updateOrCreate(
                ['language_id' => $translation['language_id']],
                [
                    'page_title' => $translation['page_title'],
                    'page_content' => $translation['page_content'],
                ]
            );
        }

        return $page->load('translations.language');
    }

    /**
     * Toggle status between "active" and "inactive".
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function toggleStatus(int $id): Page
    {
        $page = $this->findOrFail($id);

        // Publishing/unpublishing affects the whole page regardless of language.
        $page->status = $page->status === 'active' ? 'inactive' : 'active';
        $page->save();

        return $page;
    }

    /**
     * Delete a dynamic page by ID.
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }

    /**
     * Normalize a multi-language payload into translation rows.
     *
     * @param  array<string, mixed>  $data
     * @return array<int, array{language_id:int, page_title:string, page_content:string}>
     */
    private function normalizeTranslations(array $data): array
    {
        if (! empty($data['translations']) && is_array($data['translations'])) {
            return collect($data['translations'])
                ->filter(fn ($translation) => is_array($translation))
                ->map(function (array $translation): array {
                    // Trim editor input so validation and duplicate checks work consistently.
                    return [
                        'language_id' => (int) $translation['language_id'],
                        'page_title' => trim((string) $translation['page_title']),
                        'page_content' => trim((string) $translation['page_content']),
                    ];
                })
                ->filter(function (array $translation): bool {
                    // Skip incomplete or visually empty translations.
                    return $translation['language_id'] > 0
                        && $translation['page_title'] !== ''
                        && $this->plainText($translation['page_content']) !== '';
                })
                ->values()
                ->all();
        }

        return [];
    }

    private function plainText(?string $value): string
    {
        // Rich text editors may submit HTML-only content, so convert it to
        // plain text before deciding whether a translation is empty.
        $text = html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);

        return trim($text);
    }
}
