<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Interfaces\SystemUserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class SystemUserService implements SystemUserServiceInterface
{
    /**
     * @return Builder<User>
     */
    public function getAllLatestQuery(): Builder
    {
        return User::query()->latest();
    }

    /**
     * @return array<int, string>
     */
    public function getRoleOptions(): array
    {
        return UserRole::values();
    }

    /**
     * @param array{name:string,username:string,email:string,password:string,role:string} $data
     * @param UploadedFile|null $avatar
     * @return User
     */
    public function create(array $data, ?UploadedFile $avatar = null): User
    {
        $user = new User();
        // Persist both display name and login identifier because the users table requires both.
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->password = Hash::make($data['password']);

        if ($avatar !== null) {
            $user->avatar = fileUploadStorage(
                $avatar,
                'avatar',
                $data['name'] . '-' . time()
            );
        }

        $user->save();

        return $user;
    }

    /**
     * @param User $systemUser
     * @param array{name:string,username:string,password?:string,role:string} $data
     * @param UploadedFile|null $avatar
     * @return User
     */
    public function update(User $systemUser, array $data, ?UploadedFile $avatar = null): User
    {
        if ($avatar !== null) {
            $oldAvatar = $systemUser->avatar;
            $systemUser->avatar = fileUploadStorage(
                $avatar,
                'avatar',
                $data['name'] . '-' . $systemUser->id . '-' . time()
            );

            if ($oldAvatar) {
                fileDelete(public_path($oldAvatar));
            }
        }

        if (!empty($data['password'])) {
            $systemUser->password = Hash::make($data['password']);
        }

        $systemUser->name = $data['name'];
        $systemUser->username = $data['username'];
        $systemUser->role = $data['role'];
        $systemUser->save();

        return $systemUser;
    }

    /**
     * @param int|string $id
     * @return User
     */
    public function toggleStatus(int|string $id): User
    {
        $systemUser = User::findOrFail($id);
        $systemUser->status = !$systemUser->status;
        $systemUser->save();

        return $systemUser;
    }

    /**
     * @param User $systemUser
     * @param int $authUserId
     */
    public function delete(User $systemUser, int $authUserId): void
    {
        if ($systemUser->id === $authUserId) {
            throw new InvalidArgumentException("Can't delete own id ...");
        }

        if (!empty($systemUser->avatar)) {
            fileDelete(public_path($systemUser->avatar));
        }

        $systemUser->delete();
    }
}
