<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResources;
use App\Http\Resources\userbookwaiting;
use App\Http\Resources\userbookwaitingResource;
use App\Models\books;
use App\Models\categories;
use App\Models\offer;
use App\Models\User;
use App\Models\UserBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminFilterSystemController extends Controller
{
    public function numberBooks(){
        return success_response(books::count());
    }

    public function numberUser(){
        return success_response(User::where('role','user')->count());
    }

    public function booksMostSold(){
        return success_response(books::whereHas('userBooks')->withCount('userBooks')->orderBy('user_books_count','desc')->first());
    }

    public function biggestBuyer(){
        return success_response(User::whereHas('userBooks')->withCount('userBooks')->orderBy('user_Books_count','desc')->first());
    }

    public function numbooksSold(){
        return success_response(UserBooks::count());
    }

    public function numUserSolidBooks(){
        return success_response(User::whereHas('userBooks')->count());
    }

    public function numUserSolidBook($id){
        return success_response(UserBooks::where('book_id',$id)->count());
    }
    
    public function lastCategory(){
        return success_response(categories::orderBy('created_at','desc')->take(5)->get());
    }

    public function lastUsers(){
        return success_response(User::where('role','user')->orderBy('created_at','desc')->take(5)->get());
    }
    public function lastBooks(){
        return success_response(books::orderBy('created_at','desc')->take(5)->get());
    }
    public function lastOffers(){
        return success_response(offer::with('books')->orderBy('created_at','desc')->take(5)->get());
    }


    public function CountuserBookStatus(){ 
       return success_response([
            'accept_book' => UserBooks::where('status','accept')->count(),
            'rejected_book' => UserBooks::where('status','rejected')->count(),
            'waiting_book' => UserBooks::where('status','waiting')->count(),
        ]);
    }

    // public function CountuserBookWaiting(){
    //     return success_response(books::whereHas('userBooks',function($query){
    //         $query->where('status','waiting');
    //     })->withCount('userBooks')->orderBy('user_Books_count','desc')->first());   
    // }

    // public function CountuserBookRejected(){
    //     return success_response(books::whereHas('userBooks',function($query){
    //         $query->where('status','rejected');
    //     })->withCount('userBooks')->orderBy('user_Books_count','desc')->first());   
    // }

    public function userBookAccepted(){
        return success_response(books::with('userBooks.user')->whereHas('userBooks',function($query){
            $query->where('status','accept');
        })->orderBy('created_at','desc')->paginate(20));   
    }

    public function userBookWaiting(){
        return success_response( books::with('userBooks.user')->whereHas('userBooks',function($query){
            $query->where('status','waiting');
        })->orderBy('created_at','desc')->paginate(20));   
    }

    public function userBookRejected(){
        return success_response(books::with('userBooks.user')->whereHas('userBooks',function($query){
            $query->where('status','rejected');
        })->orderBy('created_at','desc')->paginate(20));   
    }

    public function all_user_book(){
        return success_response(UserBooks::with(['books','user'])->latest()->paginate(40));
    }

}
