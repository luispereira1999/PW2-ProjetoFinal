<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
        'votes_amount',
        'user_id',
        'post_id'
    ];
}
