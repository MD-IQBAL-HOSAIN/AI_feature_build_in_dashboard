<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    /**
     * Store or update the FCM token and device ID for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'nullable|string',
                'device_id' => 'nullable|string',
            ]);


            // If validation fails
            if ($validator->fails()) {
                return jsonResponse(false, $validator->messages(), 422);
            }

            // Get authenticated user
            $user = auth()->user();

            // Update or create FCM token and device_id
            $user->update([
                'fcm_token' => $request->fcm_token,
                'device_id' => $request->device_id,
            ]);

            // Return success response
            return jsonResponse(
                true,
                'FCM token and Device ID updated successfully.',
                200,
                [
                    'name' => $user->name,
                    'fcm_token' => $request->fcm_token,
                    'device_id' => $request->device_id,
                ]
            );
        } catch (\Exception $e) {
            // Return error response in case of failure
            return jsonErrorResponse(
                'FCM token and Device ID update failed',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Delete the FCM token and device ID for the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Check if the provided ID matches the user's ID
            if ($user->id != $id) {
                return jsonResponse(false, 'Unauthorized action.', 403);
            }

            // Clear FCM token and device_id
            $user->update([
                'fcm_token' => null,
                'device_id' => null,
            ]);

            // Return success response
            return jsonResponse(
                true,
                'FCM token and Device ID deleted successfully.',
                200,
                [
                    'name' => $user->name,
                    'fcm_token' => null,
                    'device_id' => null,
                ]
            );
        } catch (\Exception $e) {
            // Return error response in case of failure
            return jsonErrorResponse('FCM token and Device ID deletion failed', 500, ['error' => $e->getMessage()]);
        }
    }
}







