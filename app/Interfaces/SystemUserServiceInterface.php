<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;

interface SystemUserServiceInterface
{
    /**
     * Get latest system user query for DataTable.
     *
     * @return Builder<User>
     */
    public function getAllLatestQuery(): Builder;

    /**
     * Get available role options.
     *
     * @return array<int, string>
     */
    public function getRoleOptions(): array;

    /**
     * Create a system user and optionally upload avatar.
     *
     * @param array{name:string,username:string,email:string,password:string,role:string} $data
     * @param UploadedFile|null $avatar
     * @return User
     */
    public function create(array $data, ?UploadedFile $avatar = null): User;

    /**
     * Update a system user and optionally replace avatar.
     *
     * @param User $systemUser
     * @param array{name:string,username:string,password?:string,role:string} $data
     * @param UploadedFile|null $avatar
     * @return User
     */
    public function update(User $systemUser, array $data, ?UploadedFile $avatar = null): User;

    /**
     * Toggle active status for a system user.
     *
     * @param int|string $id
     * @return User
     */
    public function toggleStatus(int|string $id): User;

    /**
     * Delete a system user.
     *
     * @param User $systemUser
     * @param int $authUserId
     */
    public function delete(User $systemUser, int $authUserId): void;
}
