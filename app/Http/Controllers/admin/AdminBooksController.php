<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\book\StorebooksRequest;
use App\Models\books;
use App\Models\User;
use App\Models\UserBooks;
use App\Trait\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBooksController extends Controller
{
    use NotificationTrait;

    public function index()
    {
        $books = books::paginate(20);
        return success_response($books);
    }

    
    public function get_books_category($id){
        $books = books::select('id','name','price','image','file','description')->with('offer')->where('category_id',$id)->paginate(20);
        return success_response($books);
    }

    public function get_books_withoutcategory(){
        $books = books::select('id','name','price','image','file','description')->with('offer')->whereNull('category_id')->paginate(20);
        return success_response($books);
    }

    public function books_with_offer(){
        $booksOffer = books::whereHas('offer',function($q){
           $q->where('from_date' ,'<' ,now())->where('to_date' ,'>', now());
       })->paginate(20);
       $booksWithOutOffer = books::doesntHave('offer')->OrWhereHas(
           'offer',function($q){
               $q->where('from_date' ,'>', now())->OrWhere('to_date' ,'<', now());
           }
       )->paginate(20);
       return success_response(['booksWithOffer'=>$booksOffer , 'booksWithOutOffer'=>$booksWithOutOffer]);
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorebooksRequest $request)
    {
        $books = books::create($request->except('image'));
        if ($books) {
            if ($request->hasFile('image')) {
                
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('books/image', $fileName, 'public');

                $books->image = $path;
                $books->save();
            }
            if ($request->hasFile('file')) {
                
                $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
                $path = $request->file('file')->storeAs('books/file', $fileName, 'public');

                $books->file = $path;
                $books->save();
            }
        }
        return success_response($books);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $books = books::findOrFail($id);
        return success_response($books);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $books = books::findOrFail($id);
        $books->update($request->except('image'));
        if ($request->hasFile('image')) {
            if ($books->image != null) {
                Storage::disk('public')->delete($books->image);
            }
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('books/image', $fileName, 'public');
            $books->image = $path;
            $books->save();
        }

        if ($request->hasFile('file')) {
            if ($books->file != null) {
                Storage::disk('public')->delete($books->file);
            }  
            $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
            $path = $request->file('file')->storeAs('books/file', $fileName, 'public');

            $books->file = $path;
            $books->save();
        }
        return success_response(books::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userbook = UserBooks::where('book_id',$id)->first();
        if($userbook){
            return response()->json(['status' => 0, 'message' => 'هناك شخص اشترى هذا الكتاب...لذلك لايمكن حذفه'], 403);

            // return error_response('هناك شخص اشترى هذا الكتاب...لذلك لايمكن حذفه');
        }
        $books = books::findOrFail($id);

        if($books->image != null){
            Storage::disk('public')->delete($books->image);
        }
        $books->delete();
        return success_response();
    }

    public function changeStatusUserBook(Request $request){
        $request->validate([
            'user_book_id'=>['required','exists:user_books,id'],
            'status'=>['required','in:accept,rejected'],
            'reason'=>['nullable','string'],
        ]);
        $user_book = UserBooks::find($request['user_book_id']);
        $user_book->status = $request['status'];
        $user_book->save();

        if($request['status']=='accept'){
            $this->send_event_notification(User::find($user_book->user_id),'','تم قبول طلبك','Your request has been accepted',$user_book->id);
        }
        elseif($request['status']=='rejected'){
            $this->send_event_notification(User::find($user_book->user_id), '' , $request['reason'].'تم رفض طلبك' , 'Your request has been rejected'.$request['reason'],$user_book->id);
        }
        return success_response();
    }
    
    
}
