<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\PostVoteService;
use App\Models\Post;

class PostService
{
    protected $postVoteService;

    public function __construct(PostVoteService $postVoteService)
    {
        $this->postVoteService = $postVoteService;
    }


    public function getAll($userId)
    {
        $posts = DB::table('posts')
            ->select(
                'posts.id',
                'posts.title',
                'posts.description',
                'posts.date',
                'posts.votes_amount',
                'posts.comments_amount',
                'posts.user_id              AS post_user_id',
                'users.name                 AS post_user_name',
                'posts_votes.user_id        AS vote_user_id',
                'posts_votes.vote_type_id   AS vote_type_id'
            )
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($userId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $userId);
            })
            ->orderByDesc('posts.date')
            ->get();

        return $posts;
    }


    public function getAllByUser($userId, $loggedUserId)
    {
        $posts = DB::table('posts')
            ->select(
                'posts.id',
                'posts.title',
                'posts.description',
                'posts.date',
                'posts.votes_amount',
                'posts.comments_amount',
                'posts.user_id              AS post_user_id',
                'users.name                 AS post_user_name',
                'posts_votes.user_id        AS vote_user_id',
                'posts_votes.vote_type_id   AS vote_type_id'
            )
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($loggedUserId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->where('posts.user_id', '=', $userId)
            ->orderByDesc('posts.date')
            ->get();

        return $posts;
    }


    public function getOneByMostVotes($userId)
    {
        $post = DB::table('posts')
            ->select(
                'posts.id',
                'posts.title',
                'posts.description',
                'posts.date',
                'posts.votes_amount',
                'posts.comments_amount',
                'posts.user_id              AS post_user_id',
                'users.name                 AS post_user_name',
                'posts_votes.user_id        AS vote_user_id',
                'posts_votes.vote_type_id   AS vote_type_id'
            )
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($userId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $userId);
            })
            ->orderByDesc('posts.votes_amount')
            ->first();

        return $post;
    }


    public function getOne($postId)
    {
        $post = Post::findOrFail($postId);
        return $post;
    }


    public function getOneWithUserVote($postId, $userId)
    {
        $post = DB::table('posts')
            ->select(
                'posts.id',
                'posts.title',
                'posts.description',
                'posts.date',
                'posts.votes_amount',
                'posts.comments_amount',
                'posts.user_id              AS post_user_id',
                'users.name                 AS post_user_name',
                'posts_votes.user_id        AS vote_user_id',
                'posts_votes.vote_type_id   AS vote_type_id'
            )
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes',  function ($query) use ($userId) {
                $query->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $userId);
            })
            ->where('posts.id', '=', $postId)
            ->first();

        return $post;
    }


    public function getAllByTitle($title, $userId)
    {
        $titleFilter = '%' . $title . '%';

        $posts = Post::select(
            'posts.id',
            'posts.title',
            'posts.description',
            'posts.date',
            'posts.votes_amount',
            'posts.comments_amount',
            'posts.user_id              AS post_user_id',
            'users.name                 AS post_user_name',
            'posts_votes.user_id        AS vote_user_id',
            'posts_votes.vote_type_id   AS vote_type_id'
        )
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($userId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $userId);
            })
            ->whereRaw('title LIKE BINARY ?', [$titleFilter])
            ->orderByDesc('votes_amount')
            ->get();

        return $posts;
    }


    public function getVotesAmount($postId)
    {
        $votesAmount = Post::where('id', $postId)
            ->pluck('votes_amount')
            ->first();

        return $votesAmount;
    }


    public function insertOne($title, $description, $date, $userId)
    {
        $post = new Post();
        $post->title = $title;
        $post->description = $description;
        $post->date = $date;
        $post->user_id = $userId;
        $post->save();

        return 'Post criado com sucesso.';
    }


    public function updateOne($post, $title, $description)
    {
        $post->title = $title;
        $post->description = $description;
        $post->save();

        return 'Post atualizado com sucesso.';
    }


    public function updateVotesAmount($postId, $votesAmount)
    {
        DB::table('posts')
            ->where('id', $postId)
            ->increment('votes_amount', $votesAmount);

        return 'Quantidade de votos do post atualizada com sucesso.';
    }


    public function vote($postId, $userId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            $postVote = $this->postVoteService->getOne($postId, $userId);

            // se já existe algum voto do utilizador para este post
            if ($postVote) {
                // se o utilizador está a votar no mesmo tipo de voto
                if ($voteTypeId == $postVote->vote_type_id) {
                    $postVote = $this->postVoteService->deleteOne($postId, $userId);

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {  // está a votar no tipo de voto contrário
                    $postVote = $this->postVoteService->updateOrInsertOne($postId, $userId, $voteTypeId);

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {  // insere novo voto
                $postVote = $this->postVoteService->insertOne($postId, $userId, $voteTypeId);

                if ($voteTypeId == 1) {
                    $votesAmount = 1;
                } else if ($voteTypeId == 2) {
                    $votesAmount = -1;
                }
            }

            $this->updateVotesAmount($postId, $votesAmount);

            DB::commit();

            return ['success' => true, 'message' => 'Post votado com sucesso.'];
        } catch (\Exception $exception) {
            DB::rollback();
            return ['success' => false, 'message' => 'Erro ao votar no post.'];
        }
    }


    public function deleteOne($post)
    {
        $post->delete();
        return 'Post removido com sucesso.';
    }
}
