<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Cloudder;
use Hash;
use Session;


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
            'name'=> 'required',
            'email'=> 'required',
            'username'=> 'required',
            'avatar'=> 'required',
            'password'=> 'required',
            'repeat_password'=> 'required'                    
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
            return redirect('/')->with('mssg','Dang ky thanh cong');
        } else {
            return redirect('/auth/register')->with('mssg', 'mat khau nhap lai khong khop');
        }
    }

    public function loginAction(Request $request)
    {
        // $user = User::where('email', $request->email)->first();
        // $e = $user->email;
        // $p = $user->password;
        $users = User::get();
        foreach ($users as $user) {
            if ($request->email != $user->email) {
                return redirect()->route('auth.login')->with('mssg', 'Dang nhap that bai');
            } else if ($user->email == $request->email && Hash::check($request->password, $user->password) == true) {
                $data = [
                    'name' => $user->name,
                    'id' => $user->id,

                ];
                Session::put($data);

                return view('auth.dashboard', compact('user'))->with('mssg', 'Dang nhap thanh cong');
            } else {
                return redirect()->route('auth.login')->with('mssg', 'Dang nhap that bai');
            }
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
        $user =User::where('id', Session::get('id'))->get();
        return view('profile.profile',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user =User::where('id', Session::get('id'))->get();
        return view('profile.edit-profile',compact('user'));

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
            'name'=> 'required',            
            'avatar'=> 'required'                              
        ]);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);

        User::where('id',Session::get('id'))->update([
            'name' => $request->name,
            'avatar'=> $imageName
        ]);

        Session::put('name',$request->name);

        return redirect()->route('auth.profile')->with('mssg', 'Cap nhat thong tin thanh cong');
    }

    public function viewReset(){
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request){
        $this->validate($request, [
            'oldPassword'=> 'required',            
            'password'=> 'required',
            'repeat_password'=> 'required'                              
        ]);

        $user = User::where('id',Session::get('id'))->first();
        if(Hash::check($request->oldPassword,$user->password)){
            if($request->password == $request->repeat_password){
                $p = Hash::make($request->password);
                User::where('id', Session::get('id'))->update(['password' => $p]);
                return redirect()->route('auth.profile')->with('mssg', 'doi mat khau thanh cong');
                
            }
            else if($request->password != $request->repeat_password)
            {
                return redirect()->route('auth.v-reset')->with('mssg', 'mat khau nhap lai khong khop');
            }
        }
        else{
            return redirect()->route('auth.v-reset')->with('mssg', 'mat khau cu khong khop');
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
}
