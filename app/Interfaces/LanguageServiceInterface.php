<?php

namespace App\Interfaces;

use App\Models\Language;
use Illuminate\Support\Collection;

interface LanguageServiceInterface
{
    /**
     * Retrieve all language records ordered by latest first.
     *
     * @return Collection<int, Language>
     */
    public function getAllLatest(): Collection;

    /**
     * Retrieve a language by ID.
     *
     * @param int $id
     * @return Language
     */
    public function findOrFail(int $id): Language;

    /**
     * Create and persist a new language.
     *
     * @param array{name:string,code:string,status?:string} $data
     * @return Language
     */
    public function create(array $data): Language;

    /**
     * Update an existing language.
     *
     * @param int $id
     * @param array{name?:string,code?:string,status?:string} $data
     * @return Language
     */
    public function update(int $id, array $data): Language;

    /**
     * Toggle language status.
     *
     * @param int $id
     * @return Language
     */
    public function toggleStatus(int $id): Language;

    /**
     * Delete a language.
     *
     * @param int $id
     */
    public function delete(int $id): void;
}

