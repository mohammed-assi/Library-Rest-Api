<?php

namespace App\Http\Controllers;

use App\Models\fcm_tokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    public function store_FcmToken(Request $request){
        $validator = Validator::make($request->all(), [   
            'fcm_token' => 'required|string',
            'device_id' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'status' => 400], 400);
        }
        $fcm_token = fcm_tokens::where('device_id',$request['device_id'])->first();
        
        if($fcm_token){
            $fcm_token->update(['token'=>$request['fcm_token']]);
        }
        else{
            fcm_tokens::create([
                'user_id' =>  auth()->user()->id,
                'token' => $request['fcm_token'],
                'device_id' => $request['device_id']
            ]);
        }
        return response()->json(['message' => 'successfully', 'status' => 200]);
    }

    public function change_lang(Request $request){
        $validator = Validator::make($request->all(), [   
            'lang' => 'in:ar,en',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'status' => 400], 400);
        }
        fcm_tokens::where('user_id',auth()->user()->id)->update([
            'lang' => $request['lang']
        ]);
        return response()->json(['message' => 'successfully', 'status' => 200]);
    }
}
