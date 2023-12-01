<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBooks extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function books(){
        return $this->belongsTo(books::class,'book_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
