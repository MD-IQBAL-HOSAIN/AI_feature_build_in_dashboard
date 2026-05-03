<?php

namespace App\Http\Controllers\Web\Backend\V1\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MailController extends Controller
{
    public function index(){
        return view("backend.v1.settings.mail-settings");
    }

    public function update(Request $request) {
        $request->validate([
            'mail_mailer'       => 'nullable|string',
            'mail_host'         => 'nullable|string',
            'mail_port'         => 'nullable|string',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_encryption'   => 'nullable|string',
            'mail_from_address' => 'nullable|string',
        ]);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak  = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s*/',
                '/MAIL_HOST=(.*)\s*/',
                '/MAIL_PORT=(.*)\s*/',
                '/MAIL_USERNAME=(.*)\s*/',
                '/MAIL_PASSWORD=(.*)\s*/',
                '/MAIL_ENCRYPTION=(.*)\s*/',
                '/MAIL_FROM_ADDRESS=(.*)\s*/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            File::put(base_path('.env'), $envContent);

            return back()->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update ... '.$e->getMessage());
        }
    }
}








