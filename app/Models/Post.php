<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\PostsVote;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'description',
        'date',
        'votes_amount',
        'comments_amount',
        'user_id'
    ];

    public static function allInHome()
    {
        $userId = 1; // Substitua o valor 1 pela variável desejada.

        $posts = DB::table('posts as p')
            ->select(
                'p.id as post_id',
                'p.title',
                'p.description',
                'p.date',
                'p.votes_amount',
                'p.comments_amount',
                'p.user_id as post_user_id',
                'u.name as post_user_name',
                'v.user_id as vote_user_id',
                'v.vote_type_id'
            )
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('posts_votes as v', function ($join) use ($userId) {
                $join->on('p.id', '=', 'v.post_id')
                    ->where('v.user_id', '=', $userId);
            })
            ->orderBy('p.votes_amount', 'desc')
            ->get();

        return $posts;
    }

    public function votes()
    {
        return $this->hasMany(PostsVote::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public static function oneInPost($userLoggedId, $postId)
    {
        $post = DB::table('posts as p')
            ->select('p.*', 'users.name as post_user_name', 'posts_votes.user_id as vote_user_logged_id', 'posts_votes.vote_type_id')
            ->join('users', 'p.user_id', '=', 'users.id')
            ->leftJoin('posts_votes',  function ($query) use ($userLoggedId) {
                $query->on('p.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $userLoggedId);
            })
            ->where('p.id', '=', $postId)
            ->first();

        return $post;
    }
}
