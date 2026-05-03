<?php

namespace App\Http\Controllers\Web\Backend\V1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ThirdPartyApiController extends Controller
{

    /**
     * Display the third-party API settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("backend.v1.settings.third-party-api");
    }




    /**
     * Update the third-party API settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'bible_api_key' => 'nullable|string',
            'bible_api' => 'nullable|string',
        ]);

        try {
            // Remove spaces from the input values
            $bibleApiKey = str_replace(' ', '', $request->bible_api_key);
            $bibleApi = str_replace(' ', '', $request->bible_api);

            // Get the content of the .env file
            $envContent = File::get(base_path('.env'));
            $lineBreak  = "\n";

            // Replace the API key and API URL in the .env file content
            $envContent = preg_replace([
                '/BIBLE_API_KEY=(.*)\s*/',
                '/BIBLE_API=(.*)\s*/',
            ], [
                'BIBLE_API_KEY=' . $bibleApiKey . $lineBreak,
                'BIBLE_API=' . $bibleApi . $lineBreak,
            ], $envContent);

            // Write the updated content back to the .env file
            File::put(base_path('.env'), $envContent);

            // Return success response
            return back()->with('t-success', 'Updated successfully');
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return back()->with('t-error', 'Failed to update ... ' . $e->getMessage());
        }
    }
}








