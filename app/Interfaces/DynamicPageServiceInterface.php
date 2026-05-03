<?php

namespace App\Interfaces;

use App\Models\Page;
use Illuminate\Support\Collection;

/**
 * Defines translation-aware domain operations for dynamic pages.
 *
 * Controllers interact with this contract so persistence details remain inside
 * the service layer instead of leaking into HTTP actions.
 */
interface DynamicPageServiceInterface
{
    /**
     * Retrieve all dynamic pages ordered by latest first.
     *
     * @return Collection<int, Page>
     */
    public function getAllLatest(): Collection;

    /**
     * Retrieve a dynamic page by ID.
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Page;

    /**
     * Create and persist a new dynamic page.
     *
     * @param  array<string, mixed>  $data  Validated payload.
     */
    public function create(array $data): Page;

    /**
     * Update an existing dynamic page.
     *
     * @param  int  $id  Dynamic page identifier.
     * @param  array<string, mixed>  $data  Validated payload.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $id, array $data): Page;

    /**
     * Toggle dynamic page status between "active" and "inactive".
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function toggleStatus(int $id): Page;

    /**
     * Delete a dynamic page by ID.
     *
     * @param  int  $id  Dynamic page identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): void;
}
