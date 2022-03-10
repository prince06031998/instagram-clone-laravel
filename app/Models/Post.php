<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guard=[];

    protected $casts = [
        'images' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
}
