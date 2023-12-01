<?php

use App\Http\Controllers\admin\AdminBooksController;
use App\Http\Controllers\admin\AdminCategoriesController;
use App\Http\Controllers\admin\AdminFilterSystemController;
use App\Http\Controllers\admin\AdminOfferController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\NotificationsController;
use App\Http\Controllers\AuthController as UserAuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\FcmTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('admin/login', [AuthController::class,'login']);

Route::get('books',[BooksController::class,'get_all_books']);
Route::get('Category_books/{id}',[BooksController::class,'get_books_category']);
Route::get('booksWithOutCategory',[BooksController::class,'get_books_withoutcategory']);

Route::get('category',[BooksController::class,'get_category']);
Route::get('offer',[BooksController::class,'offer']);


Route::post('login',[UserAuthController::class,'login']);
Route::post('register',[UserAuthController::class,'register']);
Route::post('forgetpasswor',[UserAuthController::class,'forgetpasswor']);
Route::post('resetPassword',[UserAuthController::class,'resetPassword']);
Route::post('check_code',[UserAuthController::class,'check_code']);

Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::get('Auth_books',[BooksController::class,'Auth_get_all_books']);
    Route::get('Auth_Category_books/{id}',[BooksController::class,'Auth_get_books_category']);
    Route::get('Auth_booksWithOutCategory',[BooksController::class,'Auth_get_books_withoutcategory']);

    Route::get('mybook',[BooksController::class,'mybook']);
    Route::post('buy_book',[BooksController::class,'buy_book']);
    Route::post('download_book',[BooksController::class,'download_book']);

    Route::post('store_FcmToken',[FcmTokenController::class,'store_FcmToken']);
    Route::get('notifications',[UserAuthController::class,'notifications']);
    Route::get('user_book_details/{id}',[BooksController::class,'user_book_details']);

});


Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => ['auth:sanctum','Admin']], function(){
        Route::resource('books',AdminBooksController::class);
        Route::get('books_filter_offer',[AdminBooksController::class,'books_with_offer']);
        Route::post('updatebooks/{id}',[AdminBooksController::class,'update']);

        Route::get('Category_books/{id}',[AdminBooksController::class,'get_books_category']);
        Route::get('booksWithOutCategory',[AdminBooksController::class,'get_books_withoutcategory']);

        Route::get('books_filter_offer',[AdminBooksController::class,'books_with_offer']);
        Route::post('updatebooks/{id}',[AdminBooksController::class,'update']);

        Route::resource('category',AdminCategoriesController::class);
        Route::post('category/{id}',[AdminCategoriesController::class,'update']);

        Route::resource('offer',AdminOfferController::class);

        Route::get('all_user', [AdminUserController::class,'all_user']);
        Route::get('user_book/{user}',[AdminUserController::class,'user_book']);

        Route::get('numberBooks',[AdminFilterSystemController::class,'numberBooks']);
        Route::get('numberUser',[AdminFilterSystemController::class,'numberUser']);
        Route::get('booksMostSold',[AdminFilterSystemController::class,'booksMostSold']);
        Route::get('biggestBuyer',[AdminFilterSystemController::class,'biggestBuyer']);
        Route::get('numbooksSold',[AdminFilterSystemController::class,'numbooksSold']);
        Route::get('numUserSolidBooks',[AdminFilterSystemController::class,'numUserSolidBooks']);
        Route::get('numUserSolidBook/{id}',[AdminFilterSystemController::class,'numUserSolidBook']);
        Route::get('lastCategory',[AdminFilterSystemController::class,'lastCategory']);
        Route::get('lastUsers',[AdminFilterSystemController::class,'lastUsers']);
        Route::get('lastBooks',[AdminFilterSystemController::class,'lastBooks']);
        Route::get('lastOffers',[AdminFilterSystemController::class,'lastOffers']);

        Route::get('CountuserBookStatus',[AdminFilterSystemController::class,'CountuserBookStatus']);
        Route::get('userBookAccepted',[AdminFilterSystemController::class,'userBookAccepted']);
        Route::get('userBookWaiting',[AdminFilterSystemController::class,'userBookWaiting']);
        Route::get('userBookRejected',[AdminFilterSystemController::class,'userBookRejected']);
        Route::get('allUserBook',[AdminFilterSystemController::class,'all_user_book']);
        
        Route::post('changeStatusBuyBook',[AdminBooksController::class,'changeStatusUserBook']);
    
        Route::get('Notification',[NotificationsController::class,'notifications']);        
    
        Route::get('user_book_details/{id}',[AdminUserController::class,'user_book_details']);
    });

});
  

