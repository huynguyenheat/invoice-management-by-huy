<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string',
            'password'=>'required|string',
        ]);
        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
        ]);
        //$token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user'=>$user,
            //'token'=>$token
        ];
        return response($response,201);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('invoice/list');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // public function login(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'email|required',
    //             'password' => 'required'
    //         ]);

    //         $credentials = request(['email', 'password']);

    //         if (!Auth::attempt($credentials)) {
    //             return response()->json([
    //                 'status_code' => 500,
    //                 'message' => 'Unauthorized'
    //             ]);
    //         }

    //         $user = User::where('email', $request->email)->first();

    //         if (!Hash::check($request->password, $user->password, [])) {
    //             throw new \Exception('Error in Login');
    //         }

    //         $tokenResult = $user->createToken('authToken')->plainTextToken;

    //         return response()->json([
    //             'status_code' => 200,
    //             'access_token' => $tokenResult,
    //             'token_type' => 'Bearer',
    //         ]);
    //     } catch (\Exception $error) {
    //         return response()->json([
    //             'status_code' => 500,
    //             'message' => 'Error in Login',
    //             'error' => $error,
    //         ]);
    //     }
    // }

    // public function login(Request $request){

    //     $credentials = $request->validate([
    //         'email' => ['required'],
    //         'password' => ['required'],
    //     ]);

        
    //     $user = User::whereEmail($credentials['email'])->first();
        
    //     if(Auth::attempt($credentials)){
    //         return response()->json([
    //             'message' => 'login success',
    //             // send token to front-end
    //             'token' => $user->createToken("API TOKEN")->plainTextToken
    //         ], 200);
    //     }

    //     return response()->json([
    //         'message' => 'wrong email or password'
    //     ], 500);
    // }

    // public function login(Request $request){
    //     $fields = $request->validate([
    //         'email'=> 'required',
    //         'password'=>'required',
    //     ]);
    //     $user = User::where('email',$fields['email'])->first();
    //     if(!$user||!Hash::check($fields['password'],$user->password)){
    //         return response([
    //             'message'=>'Bad Credentials',
    //         ], 401);
    //     }
    //     $token = $user->createToken('myapptoken')->plainTextToken;
    //     $response = [
    //         'user'=>$user,
    //         'token'=>$token
    //     ];
    //     return response($response,201);
    // }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        // $request->user('sanctum')->currentAccessToken()->delete();
        return [
            'message'=>'Logged out'
        ];
    }
}