<?php

namespace App\Interfaces;

use App\Models\Faq;
use Illuminate\Support\Collection;

/**
 * Defines the FAQ domain operations consumed by web/API layers.
 *
 * The implementation is resolved by Laravel's service container, allowing
 * controllers to depend on an abstraction instead of a concrete class.
 */
interface FaqServiceInterface
{
    /**
     * Retrieve all FAQ records ordered by latest first.
     *
     * @return Collection<int, Faq>
     */
    public function getAllLatest(): Collection;

    /**
     * Retrieve a single FAQ by its primary key.
     *
     * @param int $id FAQ identifier.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Faq;

    /**
     * Create and persist a new FAQ record.
     *
     * @param array<string, mixed> $data Validated payload.
     * @return Faq
     */
    public function create(array $data): Faq;

    /**
     * Update an existing FAQ record.
     *
     * @param int $id FAQ identifier.
     * @param array<string, mixed> $data Validated payload.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $id, array $data): Faq;

    /**
     * Toggle FAQ status between "active" and "inactive".
     *
     * @param int $id FAQ identifier.
     * @return Faq
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function toggleStatus(int $id): Faq;

    /**
     * Delete a FAQ record.
     *
     * @param int $id FAQ identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): void;
}
