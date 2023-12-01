<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function notifications(){
        return success_response(Notifications::where('user_id',auth()->user->id)->latest()->paginate(40));
    }
}
