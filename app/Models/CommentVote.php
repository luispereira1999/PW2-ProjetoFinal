<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'vote_type_id'
    ];
}
