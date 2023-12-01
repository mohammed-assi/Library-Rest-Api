<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class books extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden  =['pivot'];
    
    public function Category(){
        return $this->belongsTo(categories::class,'category_id');
    }
    
    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function offer(){
        return $this->hasMany(offer::class,'book_id');
    }

    public function userBooks(){
        return $this->hasMany(UserBooks::class,'book_id');
    }

    // public function RelationWithUser()
    // {
    //     return $this->belongsToMany(User::class,UserBooks::class,
    //         'book_id',
    //         'user_id'
    //     )->where('user_id',auth()->user()->id)->select('status');
    // }
    
    public function RelationWithUser()
    {
        return $this->hasMany(UserBooks::class,'book_id')->where('user_id',auth()->user()->id);
    }


}
