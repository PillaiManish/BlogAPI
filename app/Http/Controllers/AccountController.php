<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\User;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    // Function for Login
    public function login(Request $request){
        $input = Validator::make($request->all(),[
            'email'     =>  'required|email',
            'password'  =>  'required|min:8'
        ]);

        if ($input->fails()){
            return response()->json([
                'status' => 'Error',
                'message'=> 'Please check if inputs are correct'
            ],401);
        }

        if (! $token = auth()->attempt(['email'=>$request->email,'password'=>$request->password])){
            return response()->json([
                'status' => 'Error',
                'message'=> 'Wrong username or password'
            ],401);
        }

        return $this->respondWithToken($token);
    }


    // Function for register
    public function register(Request $request){
        $input = Validator::make($request->all(),[
            'first_name'    =>  'required|min:2',
            'last_name'     =>  'required',
            'email'         =>  'required|email|unique:users',
            'password'      =>  'required|min:8'
        ]);  
        
        if ($input->fails()){
            return response()->json([
                'status' => 'Error',
                'message'=> 'Please check if input are correct'
            ],401);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => app('hash')->make($request->password),
        ]);

        return response()->json([
            'status'    => 'Success',
            'message'   => 'User has been successfully registered'
        ],200);
    }

    // Function for logout  
    public function logout(Request $request){
        auth()->logout();

        return response()->json([
            'status'    => 'Success',
            'message'   => 'You have been successfully logout'
        ],200);
    }

    // Function for Delete account
    public function delete(Request $request){
        User::find(auth()->user()->id)->delete();

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Your account has been successfully deleted'
        ], 200);        
    }

    // Function for change Password
    public function changePassword(Request $request){
        $input = Validator::make($request->all(),[
            'password'  => 'required|min:8'
        ]);
        
        if ($input->fails()){
            return response()->json([
                'status' => 'Error',
                'message'=> 'Please check if input are correct'
            ],401);
        }

        $user = User::find(auth()->user()->id);
        $user->password = app('hash')->make($request->password);
        $user->save();

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Yours password has been successfully saved'
        ],200);

    }
}
