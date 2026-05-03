<?php
namespace App\Http\Controllers\Web\Backend\V1\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $data['user']    = Auth::user();
        $data['profile'] = $data['user'];
        return view("backend.v1.settings.profile", $data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"    => "required",
            "phone"   => "nullable|string",
            "address" => "nullable|string",
        ]);
        $user = User::find(Auth::user()->id);
        $user->update($request->only('name'));

        $user->update($request->only(['address', 'phone']));

        return redirect()->back()->with('t-success', 'profile updated successfully');
    }

    public function avatar(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'avatar' => 'required|mimes:png,jpg,jpeg,ico,webp,svg,bmp,gif,tiff,avif,jfif,heic|max:2048',
            ]);
            // dd($request->all());
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            if ($request->hasFile('avatar')) {
                $user = User::find(Auth::user()->id);
                $oldAvatar = $user->avatar;

                // Use unique name so we don't delete the new file by accident
                $path = fileUpload(
                    $request->file('avatar'),
                    'avatars',
                    'user-avatar-' . $user->id . '-' . time()
                );

                if ($path === null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Avatar upload failed.',
                    ], 500);
                }

                $user->avatar = $path;
                $user->save();

                if ($oldAvatar) {
                    fileDelete(public_path($oldAvatar));
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No avatar file received.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Avatar Uploaded successfully',
                'url'     => asset($user->avatar),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function banner(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'banner' => 'required|mimes:png,jpg,jpeg,ico,webp,svg,bmp,gif,tiff,avif,jfif,heic|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            if ($request->hasFile('banner')) {
                $user = User::find(Auth::user()->id);
                $oldBanner = $user->banner;

                $path = fileUpload(
                    $request->file('banner'),
                    'banners',
                    'user-banner-' . $user->id . '-' . time()
                );

                if ($path === null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Banner upload failed.',
                    ], 500);
                }

                $user->banner = $path;
                $user->save();

                if ($oldBanner) {
                    fileDelete(public_path($oldBanner));
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No banner file received.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner Uploaded successfully',
                'url'     => asset($user->banner),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}








