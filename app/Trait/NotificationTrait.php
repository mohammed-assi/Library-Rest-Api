<?php

namespace App\Trait;

use App\Models\fcm_tokens;
use App\Models\Notifications;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

trait NotificationTrait
{
    /**
     * send notification
     *
     * @param  string  $message
     * @param  instanceof  $user
     * @return mixed
     */
    public function send_event_notification($user, string $title, string $message_ar , string $message_en,$orderId = null)
    {
        $client = new Client();
        $server_key = env('FIREBASE_SERVER_KEY');
        if ($server_key == null) {
            return;
        }

        $tokens = fcm_tokens::where('user_id', $user)->get();
       
        foreach($tokens as $token){
            $reqData['to'] = $token->token;
            $reqData['data']['title'] = $title;
            $reqData['data']['body'] = $message_ar;
            $reqData['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
            $reqData['priority'] = 'high';
            // $reqData['notification']['body'] = (app()->getLocale() == 'ar')?$message_ar : $message_en;
            $reqData['notification']['title'] = $title;
            $reqData['notification']['content_available'] = true;
            $reqData['notification']['badge'] = 0;
            $reqData['notification']['priority'] = 'high';
    
    
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $server_key,
            ])->post('https://fcm.googleapis.com/fcm/send', $reqData);

            
        }

        $notification = new Notifications();
        $notification->user_id = $user->id;
        $notification->tittle = $title;
        $notification->body = $message_ar;
        $notification->order_id = $orderId;
        $notification->save();
    }
}
