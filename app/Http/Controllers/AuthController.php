<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Toastr;
use App\Mail\RegisterMail;
use App\Mail\ResetPasswordMail;
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
        $user = User::where('status', 1)->find($id);
        $user->email_verified_at = now();
        $user->save();

        return redirect('/')->with('success', 'Email Successfully veriied');
    }

    public function auth_login(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1], $remember)) {
            $json['status'] = true;
            $json['message'] = "Success";
        } else {
            $json['status'] = false;
            $json['message'] = "Please Enter Valid email and password";
        }

        return response()->json($json);
    }

    public function forgot_password_form()
    {
        return view('frontend.auth.forgot_pass');
    }

    public function forgot_password(Request $request)
    {
        $user_email = trim($request->email);
        $user = User::where('email', $user_email)->first();
        if (!empty($user)) {
            // Generate OTP and send email
            $user->otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_expire_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new ResetPasswordMail($user));

            $toastr = app(Toastr::class);
            $toastr->success('Success', 'Email Sent Successful');

            // Redirect to the reset password form with user email
            return view('frontend.auth.otp_form', ['email' => $user_email]);
        } else {
            $toastr = app(Toastr::class);
            $toastr->error('Error', 'Please Enter Valid Email');
            return redirect()->back();
        }
    }

    public function submit_otp(Request $request)
    {
        $user_email = trim($request->email);
        $user_otp = trim($request->otp);
        $current_time = now();
        $user = User::where('email',$user_email)->where('otp',$user_otp)->where('otp_expire_at','>=',$current_time)->first();
        if(!empty($user))
        {
            $toastr = app(Toastr::class);
            $toastr->success('Success', 'Otp Confirm Successful');

            $encryptedData = encrypt(['email' => $user->email, 'otp' => $user_otp]);
            return view('frontend.auth.reset_password', ['token' => $encryptedData]);
        }
        else
        {
            $expire_user = User::where('email',$user_email)->first();
            if(!empty($expire_user))
            {
                $expire_time = $expire_user->otp_expire_at;
                if(now() > $expire_time)
                {
                    $toastr = app(Toastr::class);
                    $toastr->error('Error', 'Otp has been expired');
                    return redirect()->back();
                }
                else
                {
                    $toastr = app(Toastr::class);
                    $toastr->error('Error', 'Please Enter Valid Otp');
                    return view('frontend.auth.otp_form', ['email' => $user_email]);
                }
            }
           
        }
    }


    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $decryptedData = decrypt($request->token);
        $userEmail = $decryptedData['email'];
        $otp = $decryptedData['otp'];

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_email = trim($userEmail);
        $user = User::where('email', $user_email)->first();

        if ($user && $user->otp === $otp && $user->otp_expire_at > now()) {
            // Reset password
            $user->password = bcrypt($request->password);
            $user->save();

            // Clear OTP
            $user->otp = null;
            $user->otp_expire_at = null;
            $user->save();

            $toastr = app(Toastr::class);
            $toastr->success('Success', 'Password Reset Successful');
            return redirect('/');
        } else {
            $toastr = app(Toastr::class);
            $toastr->error('Error', 'Invalid OTP or OTP expired');
            return redirect()->back();
        }
    }

    public function auth_logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
