<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use Illuminate\Http\Request;

class DashboardApiController extends BaseController
{
    /**
     * Return authenticated user summary for dashboard bootstrap data.
     */
    public function profileRetrieval(Request $request)
    {
        try {
            $user = auth('api')->user();

            return jsonResponse(
                true,
                'Dashboard profile retrieved successfully.',
                200,
                $user?->only([
                    'id',
                    'name',
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
            return jsonErrorResponse('Failed to retrieve dashboard profile.', 500);
        }
    }
}

