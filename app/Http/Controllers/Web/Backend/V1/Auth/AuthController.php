<?php

namespace App\Http\Controllers\Web\Backend\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getSignUp(){
        return view("backend.layout.auth.signup");
    }

    public function signup(SignUpRequest $request){

        $user = User::create($request->validated());

        if( Auth::attempt(['email' => $request->email,'password'=> $request->password]) ){
            return redirect()->route('dashboard.index')->with("t-success","registration completed successfully");
        }
        return back()->with(
            'error', 'Invalid credentials provided.'
        );
    }

    public function getLogin(){
        return view("backend.layout.auth.login");
    }

    public function login(Request $request){

        $request->validate([
            "email"=> "required|email|exists:users,email",
            "password"=> "required"
        ]);

         if( Auth::attempt(['email' => $request->email,'password'=> $request->password]) ){
            $request->session()->regenerate();

            return redirect()->route('dashboard.index')->with("t-success","login completed successfully");
        }
        return back()->with(
            'error', 'Invalid credentials provided.'
        );

    }

    public function getResetPasswordForm(){
        return view('backend.layout.auth.reset-password');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'curr_password' => 'required',
            "password"=> ['required', 'string', 'min:6', 'max:8',
                // 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/',
                'confirmed'],
        ]);
        $user = User::findOrFail( auth()->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('t-success','password changed successfully');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}







