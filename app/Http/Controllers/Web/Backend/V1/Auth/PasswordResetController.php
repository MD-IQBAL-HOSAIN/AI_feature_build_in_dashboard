<?php

namespace App\Http\Controllers\Web\Backend\V1\Auth;

use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function create(){
        return view("backend.layout.auth.ask-reset-mail");
    }

    public function submitMail(Request $request){
        $request->validate([
            "email"=> "required|email",
        ]);
        try{
            $otp = $this->otpService->generateOtp($request->email);
            $this->otpService->sendOtpEmail($request->email, $otp);
        }
        catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
        $email = $request->email;
        return view("backend.layout.auth.otp-page", compact("email"));
    }


    public function submitOtp(Request $request){
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);
        $request->otp = removeSpaces($request->otp);
        if ($this->otpService->verifyOtp($request->email, $request->otp)) {
            // return response()->json(['message' => 'OTP verified']);
            Session::put('otp-flag',true);
            return view('backend.layout.auth.reset-password', ['email'=>$request->email]);
        }

        return redirect()->route('login')->with("error", "invalid OTP");
    }

    public function reset(Request $request){
        // dd($request->email);
        if(!Session::get('otp-flag')){
            return redirect()->route('login')->with('error','Unauthorized Entry Attempt');
        }
        $request->validate([
            'email'=> 'required',
            'password' => ['required', new PasswordRule()],
            'password_confirmation' => 'required'
        ]);

        Session::forget('otp-flag');
        try{
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            if( Auth::attempt(['email' => $request->email,'password'=> $request->password]) ){
                return redirect()->route('dashboard.index')->with("success","login completed successfully");
            }
            else
                return redirect()->route('login')->with("error","Login Failed");
        }
        catch(\Exception $e){
            return redirect()->route('login')->with("error", $e->getMessage());
        }
        
    }
}







