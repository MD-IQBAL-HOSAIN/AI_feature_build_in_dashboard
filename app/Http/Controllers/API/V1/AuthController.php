<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthController extends BaseController
{
    private const REGISTER_ALLOWED_ROLES = ['user', 'admin'];

    protected $otpService;

/**
 * Constructor to inject the OtpService instance.
 *
 * @param OtpService $otpService
 */
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    

    /**
     * Return the configured API JWT guard with concrete type for static analysis.
     */
    private function jwtGuard(): JWTGuard
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }


    /** Register a User.
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'role'     => ['nullable', 'string', Rule::in(self::REGISTER_ALLOWED_ROLES)],
        ]);

        if ($validator->fails()) {
            // Get all validation errors
            $errors = $validator->errors()->toArray();

            // Dynamically set the message based on the first error field
            $firstErrorField = key($errors);
            $dynamicMessage  = ucfirst($firstErrorField) . ' field is required.';

            // Prepare error messages
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = ucfirst($field) . ': ' . $message; // Add dynamic error messages
                }
            }

            return jsonErrorResponse($dynamicMessage, 422, $errorMessages);
        }

        $role = $request->input('role', 'user'); // Default to 'user' if role is not provided

        // Create the user
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,

        ]);

        // ✅ Generate JWT token for the newly registered user
        $token = $this->jwtGuard()->login($user);

        return jsonResponse(true, 'Registration successfully done', 201, [
            'user'          => [
                'id'       => $user->id,
                'username' => $user->username,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $user->role,
                'avatar'   => $user->avatar,
                'phone'    => $user->phone,
            ],
            'authorisation' => [
                'token'      => $token,
                'token_type' => 'bearer',
            ],
        ]);
    }

    /** Get a JWT via given credentials.
     * @return \Illuminate\Http\JsonResponse */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            // Get all validation errors
            $errors = $validator->errors()->toArray();

            // Dynamically set the message based on the first error field
            $firstErrorField = key($errors);
            $dynamicMessage  = ucfirst($firstErrorField) . ' field is required.';

            // Prepare error messages
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = ucfirst($field) . ': ' . $message; // Add dynamic error messages
                }
            }

            return jsonErrorResponse($dynamicMessage, 422, $errorMessages);
        }

        $identifier = $this->resolveAuthIdentifier($request);

        if ($identifier === '') {
            return $this->identifierRequiredResponse('Username or email field is required.');
        }

        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            return jsonErrorResponse('Invalid credentials', 401);
        }

        if (! $token = $this->jwtGuard()->attempt([
            'email'    => $user->email,
            'password' => $request->password,
        ])) {
            return jsonErrorResponse('Invalid credentials', 401);
        }

        $token = $this->respondWithToken($token);
        /** @var User $authUser */
        $authUser = $this->jwtGuard()->user();

        // Return successful login response with user details and token
        return jsonResponse(true, 'Login Successfully', 200, [
            'user'          => [
                'id'       => $authUser->id,
                'username' => $authUser->username,
                'name'     => $authUser->name,
                'email'    => $authUser->email,
                'role'     => $authUser->role,
                'avatar'   => $authUser->avatar,
                'phone'    => $authUser->phone,
            ],
            'authorisation' => [
                'token' => $token,
            ],
        ]);
    }

    /** Get the authenticated User.
     * @return \Illuminate\Http\JsonResponse */
    public function profile()
    {
        $success = $this->jwtGuard()->user();

        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    /** Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse */
    public function logout()
    {
        /** @var User $user */
        $user = $this->jwtGuard()->user();
        $this->jwtGuard()->logout();
        return jsonResponse(true, 'Successfully logged out', 200, [
            'user name' => $user->name,
        ]);
    }



    /** Refresh a token.
     * @return \Illuminate\Http\JsonResponse */
    public function refresh()
    {
        $success = $this->respondWithToken($this->jwtGuard()->refresh());

        return jsonResponse(true, 'New token generated', 200, [
            'user'          => [
                'id'    => $this->jwtGuard()->user()->id,
                'name'  => $this->jwtGuard()->user()->name,
                'email' => $this->jwtGuard()->user()->email,
            ],
            'authorisation' => [
                'token' => $success['access_token'],
            ],
        ]);
    }



    /** Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            // 'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }



    /**
     * Accept username or email through any common client key.
     */
    private function resolveAuthIdentifier(Request $request): string
    {
        return trim((string) (
            $request->input('identifier') ?? $request->input('login') ?? $request->input('email') ?? $request->input('username') ?? ''
        ));
    }



    /**
     * Resolve a user account by username or email.
     */
    private function findUserByIdentifier(string $identifier): ?User
    {
        if ($identifier === '') {
            return null;
        }

        return User::query()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();
    }



    /**
     * Keep identifier validation responses consistent across auth endpoints.
     */
    private function identifierRequiredResponse(string $message)
    {
        return jsonErrorResponse($message, 422, [
            'identifier' => ['The username or email field is required.'],
        ]);
    }


/**
* Forgot password flow:
* 1. User submits username/email to /forgot-password -> System generates OTP, saves
*/
    public function forgotPassword(Request $request)
    {
        $identifier = $this->resolveAuthIdentifier($request);

        if ($identifier === '') {
            return $this->identifierRequiredResponse('Forgot Password Validation failed');
        }

        // Resolve the account by username or email, then deliver OTP to the saved email.
        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            return jsonErrorResponse('No user found with this username or email.', 404);
        }

        // Generate a 6-digit reset token
        $otp = $this->otpService->generateOtp($user->email);

        // Store the token and expiry time in the database
        $user->password_reset_otp             = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry      = now()->addMinutes(5); // Token expires after 5 minutes
        $user->save();

        // Send token to the user's email (using Queue)
        Mail::to($user->email)->queue(new PasswordResetMail($otp));

        return jsonResponse(true, 'Password reset OTP has been sent to your email.', 200, [
            'OTP'   => $user->password_reset_otp,
            'email' => $user->email,
        ]);
    }



/**
 * Verify OTP
*/
    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        $identifier = $this->resolveAuthIdentifier($request);

        if ($identifier === '') {
            return $this->identifierRequiredResponse('Profile Update Validation failed');
        }

        // Find the user by username or email.
        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            return jsonErrorResponse('No user found with this username or email.', 404);
        }

        // Check if the OTP matches
        if ($user->password_reset_otp !== removeSpaces($request->otp)) {
            return jsonErrorResponse('Invalid OTP.', 400);
        }

        if (! $user->password_reset_otp) {
            return jsonErrorResponse('Unauthorizied OTP.', 401);
        }

        // Check if the OTP has expired
        if ($user->password_reset_otp_expiry < now()) {
            return jsonErrorResponse('OTP has expired.', 400);
        }

        $user->password_reset_otp_is_verified = true;
        $user->password_reset_otp_expiry      = now()->addMinutes(5);
        $user->save();
        // OTP is valid, proceed to allow password reset
        return jsonResponse(true, 'OTP verified successfully. You can now reset your password with in the next 5 mins.', 200);
    }



    /**
     * Reset password after OTP verification
     * 1. User submits new password to /reset-password along with username/email and OTP
    */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'confirmed', new PasswordRule],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        $identifier = $this->resolveAuthIdentifier($request);

        if ($identifier === '') {
            return $this->identifierRequiredResponse('Profile Update Validation failed');
        }

        // Find the user by username or email.
        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            return jsonErrorResponse('No user found with this username or email.', 404);
        }
        if (! $user->password_reset_otp_is_verified) {
            return jsonErrorResponse('Unauthorized attempt.', 401);
        }
        // Check if OTP verification is done
        if ($user->password_reset_otp === null || $user->password_reset_otp_expiry < now()) {
            $user->password_reset_otp_is_verified = false;
            $user->save();
            return jsonErrorResponse('OTP verification failed or expired. Please request a new OTP.', 400);
        }

                                                                                // If OTP is verified and not expired, proceed with password reset
        $user->password                       = Hash::make($request->password); // Hash the new password
        $user->password_reset_otp             = null;                           // Clear the otp after password reset
        $user->password_reset_otp_expiry      = null;                           // Clear the expiry
        $user->password_reset_otp_is_verified = false;
        $user->save();

        return jsonResponse(true, 'Password has been successfully reset.', 200);
    }



    /**
     * Resend OTP for password reset if the previous one is expired or not received.
      * 1. User requests OTP resend to /resend-otp along with username/email
     */
    public function resendOtp(Request $request)
    {
        $identifier = $this->resolveAuthIdentifier($request);

        if ($identifier === '') {
            return $this->identifierRequiredResponse('Profile Update Validation failed');
        }

        // Find user by username or email.
        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            return jsonErrorResponse('No user found with this username or email.', 404);
        }

        // Generate a new 6-digit reset token
        $otp = $this->otpService->generateOtp($user->email);

        // Store the new token and set expiry time
        $user->password_reset_otp             = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry      = now()->addMinutes(5); // Token expires after 5 minutes
        $user->save();

        // Send the new token to the user's email
        Mail::to($user->email)->queue(new PasswordResetMail($otp));
        // Username-based requests still send OTP to the owner's email.

        return jsonResponse(true, 'A new password reset OTP has been sent to your email.', 200, [
            'OTP'   => $otp,
            'email' => $user->email,
        ]);
    }



    /**
     * Get the authenticated user's profile.
     */
    public function profileRetrieval(Request $request)
    {
        try {
            /** @var User $user */
            $user = $this->jwtGuard()->user();

            return jsonResponse(
                true,
                'User profile retrieved successfully !!!',
                200,
                $user->only([
                    'id',
                    'name',
                    'username',
                    'email',
                    'avatar',
                    'phone',
                    'date_of_birth',
                    'position',
                    'about',
                    'address',
                    'country',
                    'city',
                    'state',
                    'created_at',
                ])
            );
        } catch (Exception $e) {
            return jsonErrorResponse('Failed to retrieve user profile.', 500);
        }
    }



    /**
     * Update the authenticated user's profile.
     */
    public function ProfileUpdate(Request $request)
    {
        /** @var User $authUser */
        $authUser          = $this->jwtGuard()->user();
        $authenticatedUser = User::find($authUser->id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|nullable|string|max:255',
            'username' => 'sometimes|nullable|string|max:255|unique:users,username,' . $authenticatedUser->id,
            'email'    => 'sometimes|nullable|email|max:255|unique:users,email,' . $authenticatedUser->id,
            'avatar'   => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,svg,webp,ico,bmp,tiff|max:5120',
            'address'  => 'sometimes|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return jsonErrorResponse(
                'Profile Update Validation failed',
                422,
                $validator->errors()->toArray()
            );
        }

        // Update only the fields that exist in request
        if ($request->filled('name')) {
            $authenticatedUser->name = $request->name;
        }

        if ($request->filled('email')) {
            $authenticatedUser->email = $request->email;
        }

        if ($request->filled('address')) {
            $authenticatedUser->address = $request->address;
        }

        if ($request->filled('username')) {
            $authenticatedUser->username = $request->username;
        }

        // Avatar handle
        if ($request->hasFile('avatar')) {
            if ($authenticatedUser->avatar) {
                fileDelete(public_path($authenticatedUser->avatar));
            }

            $avatar                    = $request->file('avatar');
            $avatarName                = $authenticatedUser->id . '_avatar';
            $avatarPath                = fileUpload($avatar, 'profile/avatar', $avatarName);
            $authenticatedUser->avatar = $avatarPath;
        }

        $authenticatedUser->save();

        return jsonResponse(
            true,
            'Profile updated successfully',
            200,
            $authenticatedUser->only(['name', 'email', 'avatar', 'address', 'username'])
        );
    }



    /**
     * Change the authenticated user's password.
     * 1. User submits old password, new password and new password confirmation to /change-password
     */
    public function ChangePassword(Request $request)
    {
        // Create custom validator using Validator facade
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password'     => 'required|string|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Authenticate the user using JWT
        // $user = JWTAuth::parseToken()->authenticate();
        /** @var User $user */
        $user = $this->jwtGuard()->user();

        if (! $user) {
            return jsonErrorResponse('User not found or unauthorized', 401);
        }

        // Check if the old password matches the current password
        if (! Hash::check($request->old_password, $user->password)) {
            return jsonErrorResponse('Old password is incorrect', 400);
        }

        // Hash the new password and save it to the database
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse(true, 'Password changed successfully', 200, $user->only(['name', 'email', 'avatar', 'username']));
    }
}
