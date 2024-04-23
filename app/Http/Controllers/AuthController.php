<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegisterMail;
use Mail;

class AuthController extends Controller
{
    public function admin_login()
    {
        if (!empty(Auth::check() && Auth::user()->is_admin == 1)) {
            return redirect('admin/dashboard');
        }
        return view('admin.auth.login');
    }
    public function auth_login_admin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => 1], $remember)) {
            $user = Auth::user();
            if ($user->status == 1) {
                return redirect('admin/dashboard');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Account has been deactivated.');
            }
        } else {
            return redirect()->back()->with('error', 'Enter valid email and password');
        }
    }

    public function admin_logout()
    {
        Auth::logout();
        return redirect('admin');
    }

    public function auth_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'User Registration Failed',
                'errors' => $validator->errors(),
                'status' => false
            ]);
        } else {
            try {

                $user = new User();
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password'));
                $user->is_admin = 0;
                $user->status = 1;
                $user->save();

                // $user = User::create([
                //     'name' => $request->input('name'),
                //     'email' => $request->input('email'),
                //     'password' => Hash::make($request->input('password')),
                //     'is_admin' => 0,
                //     'status' => 1,
                // ]);

                Mail::to($user->email)->send(new RegisterMail($user));

                Auth::login($user);

                return response()->json([
                    'message' => 'User Registration Success',
                    'status' => true
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'An error occurred during user registration.',
                    'error' => $e->getMessage(),
                    'status' => false
                ]);
            }
        }
    }

    public function verify_user($id)
    {
        $id = base64_decode($id);
        $user = User::where('status',1)->find($id);
        $user->email_verified_at = now();
        $user->save();

        return redirect('/')->with('success', 'Email Successfully veriied');
    }

    public function auth_login(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1], $remember))
        {
            $json['status'] = true;
            $json['message'] = "Success";
        }
        else
        {
            $json['status'] = false;
            $json['message'] = "Please Enter Valid email and password";
        }

        return response()->json($json);
    }

    public function auth_logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
