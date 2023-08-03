<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use App\Models\PostsVote;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'date',
        'votes_amount',
        'comments_amount',
        'user_id'
    ];

    public function votes()
    {
        return $this->hasMany(PostsVote::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
