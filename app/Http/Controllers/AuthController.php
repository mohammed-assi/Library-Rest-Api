<?php

namespace App\Http\Controllers;

use App\Http\Requests\api\loginReques;
use App\Http\Requests\api\registerReques;
use App\Mail\resetPassword;
use App\Models\codeVerfyEmail;
use App\Models\Notifications;
use App\Models\resetPassword as ModelsResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Nette\Utils\Random;

class AuthController extends Controller
{
    public function login(loginReques $request){
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);

    }

    public function register(registerReques $request){
            $data = $request->all();
            $data['password'] = Hash::make($request['password']);
            $user = User::create($data);
            if ($user) {
               
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'status' => true,
        ], 200);
    }

    public function forgetpasswor(Request $request){
        $request->validate([
            'email'=>['required','exists:users,email'],
           
        ]);
        $user =User::where('email',$request['email'])->first();
        $code = Random::generate(6, '0-9');
        ModelsResetPassword::where('email',$request['email'])->delete();
        ModelsResetPassword::create([
            'email'=>$user->email,
            'code'=> $code,
        ]);
        Mail::to($user->email)->send(new resetPassword($code,$user->name));
        return success_response();
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email'=>['required','exists:users,email'],
            'code'=>['required','exists:reset_passwords,code'],
            'password'=>['required','confirmed','min:6'],
        ]);
        $code = resetPassword::where('email',$request['email'])->first()->code;
        if($code == $request['code']){
            $user = User::where('email',$request['email'])->first();
            $user->update([
                'password'=> Hash::make($request['password']),
            ]);
            $user->save();
            $code = ModelsResetPassword::where('email',$request['email'])->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }

    }

    public function check_code(Request $request){
        $request->validate([
            'code'=>['required','exists:reset_passwords,code'],
        ]);
        return response()->json([
            'status' => true
        ], 200);

    }

    public function notifications(){
        return success_response(Notifications::where('user_id',auth()->user()->id)->latest()->paginate(40));
    }
}
