<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Collection;
use App\Interfaces\LanguageServiceInterface;

class LanguageService implements LanguageServiceInterface
{
    /**
     * Retrieve all languages ordered by latest first.
     *
     * @return Collection<int, Language>
     */
    public function getAllLatest(): Collection
    {
        return Language::latest()->get();
    }

    /**
     * Retrieve a language by ID.
     *
     * @param int $id
     * @return Language
     */
    public function findOrFail(int $id): Language
    {
        return Language::findOrFail($id);
    }

    /**
     * Create and persist a language.
     *
     * @param array{name:string,code:string,status?:string} $data
     * @return Language
     */
    public function create(array $data): Language
    {
        return Language::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Update a language.
     *
     * @param int $id
     * @param array{name?:string,code?:string,status?:string} $data
     * @return Language
     */
    public function update(int $id, array $data): Language
    {
        $language = $this->findOrFail($id);
        $language->name = $data['name'] ?? $language->name;
        $language->code = $data['code'] ?? $language->code;
        if (array_key_exists('status', $data)) {
            $language->status = $data['status'];
        }
        $language->save();

        return $language;
    }

    /**
     * Toggle language status.
     *
     * @param int $id
     * @return Language
     */
    public function toggleStatus(int $id): Language
    {
        $language = $this->findOrFail($id);
        $language->status = $language->status === 'active' ? 'inactive' : 'active';
        $language->save();

        return $language;
    }

    /**
     * Delete a language by ID.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }
}

