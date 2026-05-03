<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar',
        'banner',
        'role',
        'is_identity_verified',
        'is_available',
        'onboarding_step',
        'onboarding_completed_at',
        'status',
        'device_id',
        'fcm_token',
        'phone',
        'date_of_birth',
        'position',
        'about',
        'address',
        'country',
        'city',
        'state',
        'google_id',
        'apple_id',
        'facebook_id',
        'guest_id',
        'is_guest',
        'zip_code',
        'email_verified_at',
        'password',
        'remember_token',
        'password_reset_otp_expiry',
        'password_reset_otp',
        'password_reset_otp_is_verified',
    ];

    /**
     * Old accessor method for retrieving the avatar attribute in API requests (for Laravel 9 and below).
     * This method is used to return the avatar attribute with a URL when the request is an API request.
     * This method is deprecated and will be removed in future versions of the application.
     */

    /*  public function getAvatarAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    } */

    /**
     * Attribute method for retrieving the avatar attribute in API requests.
     * This method is used in Laravel 10 and above.
     */
    /*  protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($value) =>
            request()->is('api/*') && !empty($value)
                ? url($value)
                : $value
        );
    } */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value) || ! request()->is('api/*')) {
                    return $value;
                }

                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                }

                $path = ltrim(str_replace('\\', '/', $value), '/');

                // Handle values like "public/uploads/..." or "public/storage/..."
                if (str_starts_with($path, 'public/')) {
                    $path = ltrim(substr($path, 7), '/');
                }

                // If DB path starts with uploads/ and file exists under public/storage/uploads,
                // serve via storage URL; otherwise fallback to direct public URL.
                if (str_starts_with($path, 'uploads/') && file_exists(public_path('storage/' . $path))) {
                    return asset('storage/' . $path);
                }

                return asset($path);
            }
        );
    }

    /** Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /** Return a key value array, containing any custom claims to be added to the JWT.
     * @return array */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /*  public static function roles()
    {
        return [
            'ADMIN' => env('DEFAULT_ADMIN_ROLE', 'admin'),
            'USER' => env('DEFAULT_USER_ROLE', 'user')
        ];
    } */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'         => 'datetime',
            'is_identity_verified'      => 'boolean',
            'is_available'              => 'boolean',
            'onboarding_completed_at'   => 'datetime',
            'password'                  => 'hashed',
        ];
    }

}
