<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Cloudder;
use Hash;
use Session;
use DB;
use Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'avatar' => 'required',
            'password' => 'required',
            'repeat_password' => 'required'
        ]);

        $imageName = time() . '.' . $request->avatar->extension();

        $request->avatar->move(public_path('images'), $imageName);


        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->avatar = $imageName;

        if ($request->password == $request->repeat_password) {
            $password = $request->password;
            $hash_password = Hash::make($password);
            $user->password = $hash_password;
            $user->save();
            return redirect('/')->with('mssg', 'Dang ky thanh cong');
        } else {
            return redirect('/auth/register')->with('mssg', 'mat khau nhap lai khong khop');
        }
    }

    public function loginAction(Request $request)
    {
        // $user = User::where('email', $request->email)->first();
        // $e = $user->email;
        // $p = $user->password;
        $user = User::where('email', $request->email)->firstOrFail();
        $this->validate($request, ['email' => 'required|email']);


        if ($request->email != $user->email) {
            return redirect()->route('auth.login')->with('mssg', 'ok');
        } else if ($user->email == $request->email && Hash::check($request->password, $user->password) == true) {
            $data = [
                'name' => $user->name,
                'id' => $user->id

            ];
            Session::put($data);

            return view('auth.dashboard', compact('user'))->with('mssg', 'Dang nhap thanh cong');
        } else {
            return redirect()->route('auth.login')->with('mssg', 'Sai mat khauy');
        }




        // {
        //     $data = [
        //         'name' => $user->name,
        //         'id' => $user->id,

        //     ];
        //     Session::put($data);

        //     return view('auth.dashboard', compact('user'))->with('mssg', 'Dang nhap thanh cong');
        // }
        // else if ($e != $request->email 
        //  || Hash::check($request->password, $p != true))
        // {
        //     return redirect()->route('auth.login')->with('mssg', 'Dang nhap that bai');
        // }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect('/auth/login');
    }


    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = User::where('id', Session::get('id'))->get();
        return view('profile.profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::where('id', Session::get('id'))->get();
        return view('profile.edit-profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'avatar' => 'required'
        ]);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);

        User::where('id', Session::get('id'))->update([
            'name' => $request->name,
            'avatar' => $imageName
        ]);

        Session::put('name', $request->name);

        return redirect()->route('auth.profile')->with('mssg', 'Cap nhat thong tin thanh cong');
    }

    public function viewChangePassword()
    {
        return view('auth.changePassword');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => 'required',
            'password' => 'required',
            'repeatPassword' => 'required',
        ]);

        $user = User::where('id', Session::get('id'))->first();
        if (Hash::check($request->oldPassword, $user->password)) {
            if ($request->password == $request->repeatPassword) {
                $p = Hash::make($request->password);
                User::where('id', Session::get('id'))->update(['password' => $p]);
                return redirect()->route('auth.profile')->with('mssg', 'doi mat khau thanh cong');
            } else if ($request->password != $request->repeatPassword) {
                return redirect()->route('auth.changePassword')->with('mssg', 'mat khau nhap lai khong khop');
            }
        } else {
            return redirect()->route('auth.changePassword')->with('mssg', 'mat khau cu khong khop');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function viewForgotPassword()
    {
        return view('auth.forgotPassword');
    }

    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->firstOrFail();
        if ($request->email == $user->email) {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh')
            ]);

            Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return redirect('/')->with('mssg', 'We have e-mailed your password reset link! (Check your spam folder if you do not receive out email)');
        } else {
            return redirect('/')->with('mssg', 'cc');
        }
    }

    public function viewResetPassword($token)
    {
        return view('auth.resetPassword', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'repeatPassword' => 'required'
        ]);

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $check = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();
        $time = $now->diffInSeconds($check->created_at);
        if ($check != true) {
            return back()->with('mssg', 'Invalid Token');
        } else if ($check == true && $request->password == $request->repeatPassword) {
            if ($time < 900) {
                User::where('email', $request->email)->update([
                    'password' => Hash::make($request->password)

                ]);
                DB::table('password_resets')->where('email', $request->email)->delete();
                return redirect('/')->with('mssg', 'Reset password successfully');
            } else if ($time > 900) {
                return back()->with('mssg', 'Token is out of date. The acccess token is only available in 15 minutes');
            }
        } else {
            return back()->with('mssg', 'Ivalid repeatPassword');
        }
    }

    public function test()
    {
        $test = DB::table('password_resets')->get();
        return view('test', compact('test'));
    }
}
