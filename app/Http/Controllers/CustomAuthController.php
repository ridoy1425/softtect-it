<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\EmailVarify;
use Carbon\Carbon;

class CustomAuthController extends Controller
{
    // register page view
    public function registerShow()
    {
        return view('login.register');
    }

    public function registerStore(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:32',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(32);
        $result = $user->save();

        if ($result) {
            $user->notify(new EmailVarify($user));
            return redirect('/register')->with('success', 'Please Varify your account');
        } else {
            return redirect('login')->with('error', 'Something Wrong');
        }
    }

    public function emailvarify($id)
    {
        $user = User::where('id', $id)->update(['email_verified_at', Carbon::now()]);
    }

    // public function userLogin(Request $request)
    // {
    //     $user = Registration::where('email', $request->user_email)->first();
    //     if ($user) {
    //         if (Hash::check($request->password, $user->password)) {
    //             $request->session()->put('loginId', $user->id);
    //             return redirect('/');
    //         } else {
    //             return back()->with('error', 'Password does not matches');
    //         }
    //     } else {
    //         return back()->with('error', 'This Email does not exist');
    //     }
    // }

    // public function logout()
    // {
    //     if (Session::has('loginId')) {
    //         Session::pull('loginId');
    //         return redirect('login');
    //     }
    // }
}
