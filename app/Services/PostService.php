<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Post;

class PostService
{
    public function getAll($loggedUserId)
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
            ->orderBy('p.date', 'desc')
            ->get();

        return $posts;
    }


    public function getOneByMostVotes($loggedUserId)
    {
        $post = DB::table('posts as p')
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
            ->first();

        return $post;
    }


    public function getOne($postId)
    {
        $post = Post::find($postId);
        return $post;
    }


    public function getOneWithUserVote($postId, $loggedUserId)
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


    public function getAllByTitle($title, $loggedUserId)
    {
        $titleFilter = '%' . $title . '%';

        $posts = Post::select(
            'posts.id AS post_id',
            'posts.title AS title',
            'posts.description AS description',
            'posts.date AS date',
            'posts.votes_amount AS votes_amount',
            'posts.comments_amount AS comments_amount',
            'posts.user_id AS post_user_id',
            'users.name AS post_user_name',
            'posts_votes.user_id AS vote_user_id',
            'posts_votes.vote_type_id AS vote_type_id'
        )
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($loggedUserId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->whereRaw('title LIKE BINARY ?', [$titleFilter])
            ->orderByDesc('votes_amount')
            ->get();

        return $posts;
    }


    public function getVotesAmount($postId)
    {
        return Post::where('id', $postId)->pluck('votes_amount')->first();
    }


    public function insertOne($title, $description, $date, $loggedUserId)
    {
        $post = new Post();
        $post->title = $title;
        $post->description = $description;
        $post->date = $date;
        $post->user_id = $loggedUserId;
        $post->save();

        return ['success' => true, 'message' => 'Post criado com sucesso.'];
    }


    public function updateOne($post, $title, $description)
    {
        $post->title = $title;
        $post->description = $description;
        $post->save();

        return ['success' => true, 'message' => 'Post atualizado com sucesso.'];
    }


    public function vote($postId, $userId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            // selecionar voto
            $postVote = DB::table('posts_votes')
                ->select('post_id', 'user_id', 'vote_type_id')
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

            if ($postVote) {
                if ($voteTypeId == $postVote->vote_type_id) {
                    // remover voto
                    DB::table('posts_votes')
                        ->where('post_id', $postId)
                        ->where('user_id', $userId)
                        ->delete();

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {
                    // atualizar voto
                    DB::table('posts_votes')
                        ->updateOrInsert(
                            ['post_id' => $postId, 'user_id' => $userId],
                            ['vote_type_id' => $voteTypeId]
                        );

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {
                // inserir voto
                DB::table('posts_votes')
                    ->insert([
                        'post_id' => $postId,
                        'user_id' => $userId,
                        'vote_type_id' => $voteTypeId,
                    ]);

                if ($voteTypeId == 1) {
                    $votesAmount = 1;
                } else if ($voteTypeId == 2) {
                    $votesAmount = -1;
                }
            }

            // atualizar quantidade de votos
            DB::table('posts')
                ->where('id', $postId)
                ->increment('votes_amount', $votesAmount);

            DB::commit();

            return ['success' => true, 'message' => 'Post votado com sucesso.'];
        } catch (\Exception $exception) {
            DB::rollback();
            return ['success' => false, 'message' => 'Erro ao votar no post.'];
        }
    }


    public function delete($post)
    {
        $post->delete();

        return ['success' => true, 'message' => 'Post apagado com sucesso.'];
    }
}
