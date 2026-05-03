<?php

namespace App\Http\Controllers\Web\Backend\V1\Settings;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function index(){
        return view('backend.v1.settings.system');
    }

   //working code for file update for server
    public function update(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'logo'      => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,ico|max:5120',
            'mini_logo' => 'nullable|image|mimes:jpeg,jpg,png,svg.webp,ico|max:5120',
            'icon'      => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,ico|max:5120',
            'site_title' => 'required|string|max:255',
            'app_name'   => 'required|string|max:500',
            'admin_name' => 'required|string|max:500',
            'copyright'  => 'nullable|string|max:255',
            'contact'    => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'about'      => 'nullable|string|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $setting            = Setting::firstOrNew();
            $setting->site_title = $request->site_title;
            $setting->app_name   = $request->app_name;
            $setting->admin_name = $request->admin_name;
            $setting->copyright  = $request->copyright;
            $setting->contact    = $request->contact;
            $setting->email      = $request->email;
            $setting->about      = $request->about;
            $setting->logo      = $request->logo;
            $setting->mini_logo = $request->mini_logo;
            $setting->icon      = $request->icon;

            if ($request->hasFile('logo')) {
                if ($setting->logo && file_exists(public_path($setting->logo))) {
                    fileDelete(public_path($setting->logo));
                }
                $setting->logo = fileUpload($request->file('logo'), 'logo', $setting->logo);
            } else {
                // Retain the existing logo if no new file is uploaded
                $setting->logo = $setting->logo ?? $setting->getOriginal('logo');
            }

            if ($request->hasFile('mini_logo')) {
                if ($setting->mini_logo && file_exists(public_path($setting->mini_logo))) {
                    fileDelete(public_path($setting->mini_logo));
                }
                $setting->mini_logo = fileUpload($request->file('mini_logo'), 'mini_logo', $setting->mini_logo);
            } else {
                // Retain the existing favicon if no new file is uploaded
                $setting->mini_logo = $setting->mini_logo ?? $setting->getOriginal('mini_logo');
            }
            if ($request->hasFile('icon')) {
                if ($setting->icon && file_exists(public_path($setting->icon))) {
                    fileDelete(public_path($setting->icon));
                }
                $setting->icon = fileUpload($request->file('icon'), 'icon', $setting->icon);
            } else {
                // Retain the existing favicon if no new file is uploaded
                $setting->icon = $setting->icon ?? $setting->getOriginal('icon');
            }

            $setting->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception) {
            return back()->with('t-error', 'Failed to update');
        }
    }
}








