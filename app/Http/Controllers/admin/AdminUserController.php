<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBooks;
use App\Trait\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class AdminUserController extends Controller
{
    use NotificationTrait;

    public function all_user(){
        $user = User::all();
        return success_response($user);
    }

    public function user_book(User $user){
        return success_response(User::with('book')->find($user->id));
    }

    public function user_book_details($id){
        return success_response(UserBooks::with(['books','user'])->findOrFail($id));
    }
   
}
