<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
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

    public static function allInHome($loggedUserId)
    {
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
            ->leftJoin('posts_votes as v', function ($join) use ($loggedUserId) {
                $join->on('p.id', '=', 'v.post_id')
                    ->where('v.user_id', '=', $loggedUserId);
            })
            ->orderBy('p.votes_amount', 'desc')
            ->get();

        return $posts;
    }

    public static function allInProfile($userId, $loggedUserId)
    {
        $posts = DB::table('posts AS p')
            ->select(
                'p.id AS post_id',
                'p.title AS title',
                'p.description AS description',
                'p.date AS date',
                'p.votes_amount AS votes_amount',
                'p.comments_amount AS comments_amount',
                'p.user_id AS post_user_id',
                'u.name AS post_user_name',
                'v.user_id AS vote_user_id',
                'v.vote_type_id AS vote_type_id'
            )
            ->join('users AS u', 'p.user_id', '=', 'u.id')
            ->leftJoin('posts_votes AS v', function ($join) use ($loggedUserId) {
                $join->on('p.id', '=', 'v.post_id')
                    ->where('v.user_id', '=', $loggedUserId);
            })
            ->where('p.user_id', '=', $userId)
            ->orderByDesc('votes_amount')
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

    public static function oneInPost($loggedUserId, $postId)
    {
        $post = DB::table('posts as p')
            ->select('p.*', 'users.name as post_user_name', 'posts_votes.user_id as vote_user_logged_id', 'posts_votes.vote_type_id')
            ->join('users', 'p.user_id', '=', 'users.id')
            ->leftJoin('posts_votes',  function ($query) use ($loggedUserId) {
                $query->on('p.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->where('p.id', '=', $postId)
            ->first();

        return $post;
    }

    public static function vote($postId, $voteTypeId, $userId)
    {
        try {
            DB::beginTransaction();

            // Selecionar voto
            $postVote = DB::table('posts_votes')
                ->select('post_id', 'user_id', 'vote_type_id')
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

            if ($postVote) {
                if ($voteTypeId == $postVote->vote_type_id) {
                    // Remover voto
                    DB::table('posts_votes')
                        ->where('post_id', $postId)
                        ->where('user_id', $userId)
                        ->delete();

                    if ($voteTypeId == 1) {
                        $operation = -1;
                    } elseif ($voteTypeId == 2) {
                        $operation = 1;
                    }
                } else {
                    // Atualizar voto
                    DB::table('posts_votes')
                        ->updateOrInsert(
                            ['post_id' => $postId, 'user_id' => $userId],
                            ['vote_type_id' => $voteTypeId]
                        );

                    if ($voteTypeId == 1) {
                        $operation = 2;
                    } elseif ($voteTypeId == 2) {
                        $operation = -2;
                    }
                }
            } else {
                // Inserir voto
                DB::table('posts_votes')
                    ->insert([
                        'post_id' => $postId,
                        'user_id' => $userId,
                        'vote_type_id' => $voteTypeId,
                    ]);

                if ($voteTypeId == 1) {
                    $operation = 1;
                } elseif ($voteTypeId == 2) {
                    $operation = -1;
                }
            }

            // Atualizar quantidade de votos
            DB::table('posts')
                ->where('id', $postId)
                ->increment('votes_amount', $operation);

            DB::commit();

            return true; // retorna o estado da operação
        } catch (QueryException $exception) {
            DB::rollback();
            return null;
        }
    }
}
