<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\CommentVoteService;
use App\Models\Comment;

class CommentService
{
    protected $commentVoteService;

    public function __construct(CommentVoteService $commentVoteService)
    {
        $this->commentVoteService = $commentVoteService;
    }


    public function getAllByPostId($postId, $userId)
    {
        $comments = Comment::select([
            'c.id AS comment_id',
            'c.description AS description',
            'c.votes_amount AS votes_amount',
            'c.user_id AS comment_user_id',
            'c.post_id AS comment_post_id',
            'u.name AS comment_user_name',
            'vc.user_id AS vote_user_id',
            'vc.vote_type_id AS vote_type_id',
        ])
            ->from('comments AS c')
            ->join('users AS u', 'c.user_id', '=', 'u.id')
            ->leftJoin('comments_votes AS vc', function ($join) use ($userId) {
                $join->on('c.id', '=', 'vc.comment_id')->where('vc.user_id', '=', $userId);
            })
            ->where('c.post_id', '=', $postId)
            ->orderByDesc('votes_amount')
            ->get();

        return $comments;
    }


    public function getVotesAmount($commentId)
    {
        $votesAmount = Comment::where('id', $commentId)->pluck('votes_amount')->first();
        return $votesAmount;
    }


    public function insertOne($description, $userId, $postId)
    {
        $comment = new Comment();
        $comment->description = $description;
        $comment->user_id = $userId;
        $comment->post_id = $postId;
        $comment->save();

        return 'Comentário criado com sucesso.';
    }


    public function updateOne($comment, $description)
    {
        $comment->description = $description;
        $comment->save();

        return 'Comentário atualizado com sucesso.';
    }


    public function updateVotesAmount($commentId, $votesAmount)
    {
        DB::table('comments')
            ->where('id', $commentId)
            ->increment('votes_amount', $votesAmount);

        return 'Quantidade de votos do comentário atualizada com sucesso.';
    }



    public function vote($commentId, $userId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            $commentVote = $this->commentVoteService->getOne($commentId, $userId);

            // se já existe algum voto do utilizador para este comentário
            if ($commentVote) {
                // se o utilizador está a votar no mesmo tipo de voto
                if ($voteTypeId == $commentVote->vote_type_id) {
                    $commentVote = $this->commentVoteService->deleteOne($commentId, $userId);

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {  // está a votar no tipo de voto contrário
                    $commentVote = $this->commentVoteService->updateOrInsertOne($commentId, $userId, $voteTypeId);

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {  // insere novo voto
                $commentVote = $this->commentVoteService->insertOne($commentId, $userId, $voteTypeId);

                if ($voteTypeId == 1) {
                    $votesAmount = 1;
                } else if ($voteTypeId == 2) {
                    $votesAmount = -1;
                }
            }

            $this->updateVotesAmount($commentId, $votesAmount);

            DB::commit();

            return ['success' => true, 'message' => 'Comentário votado com sucesso.'];
        } catch (\Exception $exception) {
            DB::rollback();
            return ['success' => false, 'message' => 'Erro ao votar no comentário.'];
        }
    }


    public function deleteOne($comment)
    {
        $comment->delete();
        return 'Comentário removido com sucesso.';
    }
}
