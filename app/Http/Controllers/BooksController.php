<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Models\books;
use App\Models\categories;
use App\Models\offer;
use App\Models\User;
use App\Models\UserBooks;
use App\Trait\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    use NotificationTrait;
    public function get_all_books(){
        $books = books::select('id','name','price','image','description')->with('offer')->paginate(20);
            return success_response($books);
    }

    public function  Auth_get_all_books(){
        $books = books::select('id','file','name','price','image','description')
        ->with(['offer','RelationWithUser'])
        ->paginate(20);

        foreach($books as $book){
            if(isset($book->RelationWithUser[0]) && $book->RelationWithUser[0]->status!="accept" ){
                $book->file = null;
            }
        }

        return success_response($books);

    }
    public function get_books_category($id){
        $books = books::select('id','name','price','image','description')
        ->where('category_id',$id)->paginate(20);
        return success_response($books);    
    }

    public function Auth_get_books_category($id){
        $books = books::select('id','file','name','price','image','description')
        ->with(['offer','RelationWithUser'])
        ->where('category_id',$id)->paginate(20);

        foreach($books as $book){
            if(isset($book->RelationWithUser[0])  && $book->RelationWithUser[0]->status !="accept" ){
                $book->file= null;
            }
        }
        return success_response($books);
    }

    public function get_books_withoutcategory(){
        $books = books::select('id','name','price','image','description')->with('offer')->whereNull('category_id')->paginate(20);
        return success_response($books);
    }

    public function Auth_get_books_withoutcategory(){
        $books = books::select('id','file','name','price','image','description')
        ->with(['offer','RelationWithUser'])
        ->whereNull('category_id')->paginate(20);  
        
        foreach($books as $book){
            if(isset($book->RelationWithUser[0])  && $book->RelationWithUser[0]->status !="accept" ){
                $book->file= null;
            }
        }
        return success_response($books);
    }

    public function get_category(){
        $category = categories::all();
        return success_response($category);
    }

    public function mybook(){
        $User = User::with(['book'=>function($query){
            $query->where('status','accept')->orWhere('status','waiting')->with('RelationWithUser');
             }]
        )        
        ->find(auth()->user()->id);

        foreach($User->book as $bo){
                if(isset($bo->RelationWithUser[0]) && $bo->RelationWithUser[0]->status !="accept" ){
                    $bo->file= null;
                }
            
        }
        return success_response($User);

    }

    public function buy_book(Request $request){
        $request->validate([
            'book_id'=>['required','exists:books,id'],
            'image' => ['required','mimes:jpeg,png,jpg,gif'],
            'company'=>['required','string'],
            'AdditionalDetails'=>['nullable','string'],
        ]);
        $already_have = UserBooks::where('user_id',auth()->user()->id)->where('book_id',$request['book_id'])->first();
        if($already_have){
            if($already_have->status == "accept"){
            return response()->json(['status' => 422, 'message' => 'you already have this book'], 500);
            }
            elseif($already_have->status == "waiting"){
                return response()->json(['status' => 422, 'message' => 'you already send request buy'], 500);
            }
            else{
                $already_have->delete();
            }
        }
        $userbook= new UserBooks() ;
            $userbook->book_id = $request['book_id'];
            $userbook->user_id = auth()->user()->id;
            $userbook->company = $request['company'];
            $userbook->AdditionalDetails = $request['AdditionalDetails'];
        
        if ($request->hasFile('image')) {                
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('invoices', $fileName, 'public');
            $userbook->image = $path;
            $userbook->save();
        }
        
        $admins = User::where('role','admin')->get();
        foreach($admins as $admin){
            $this->send_event_notification($admin,'',' لديك طلب شراء..يرحى التحقق منه','You have a purchase order. Please check it',$userbook->id);
        }
        return success_response();

    }

    public function download_book(Request $request){
        $can_down = UserBooks::where('user_id',auth()->user()->id)->where('book_id',$request['book_id'])->where('status','accept')->first();
        if($can_down){
            $file = books::find($request['book_id'])->file;
            return response()->download(public_path(Storage::url($file)));
        }
        return error_response('you can\'t download this book');

    }

    public function offer(){
        return success_response(offer::with('books')->get());
    }

    public function user_book_details($id){
        $userBook = UserBooks::with(['books','user'])->findOrFail($id);
        if($userBook->user_id == auth()->user()->id){
            return success_response($userBook);
        }
        else{
            return forbidden_response();
        }
    }
}
