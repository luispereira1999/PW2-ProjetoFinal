<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Comment;

class CommentService
{
    public function getAllByPostId($postId, $loggedUserId)
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
            ->leftJoin('comments_votes AS vc', function ($join) use ($loggedUserId) {
                $join->on('c.id', '=', 'vc.comment_id')->where('vc.user_id', '=', $loggedUserId);
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

    public function insertOne($description, $loggedUserId, $postId)
    {
        $comment = new Comment();
        $comment->description = $description;
        $comment->user_id = $loggedUserId;
        $comment->post_id = $postId;
        $comment->save();

        return ['message' => 'Comentário criado com sucesso.'];
    }

    public function updateOne($comment, $description)
    {
        $comment->description = $description;
        $comment->save();

        return ['message' => 'Comentário atualizado com sucesso.'];
    }

    public function vote($commentId, $userId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            // selecionar voto
            $commentVote = DB::table('comments_votes')
                ->select('comment_id', 'user_id', 'vote_type_id')
                ->where('comment_id', $commentId)
                ->where('user_id', $userId)
                ->first();

            if ($commentVote) {
                if ($voteTypeId == $commentVote->vote_type_id) {
                    // remover comentário
                    DB::table('comments_votes')
                        ->where('comment_id', $commentId)
                        ->where('user_id', $userId)
                        ->delete();

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {
                    // atualizar comentário
                    DB::table('comments_votes')
                        ->updateOrInsert(
                            ['comment_id' => $commentId, 'user_id' => $userId],
                            ['vote_type_id' => $voteTypeId]
                        );

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {
                // inserir comentário
                DB::table('comments_votes')
                    ->insert([
                        'comment_id' => $commentId,
                        'user_id' => $userId,
                        'vote_type_id' => $voteTypeId,
                    ]);

                if ($voteTypeId == 1) {
                    $votesAmount = 1;
                } else if ($voteTypeId == 2) {
                    $votesAmount = -1;
                }
            }

            // atualizar quantidade de comentários
            DB::table('comments')
                ->where('id', $commentId)
                ->increment('votes_amount', $votesAmount);

            DB::commit();

            return true;  // retorna o estado da operação
        } catch (\Exception $exception) {
            DB::rollback();
            return ['success' => false, 'message' => 'Erro ao votar no comentário.'];
        }
    }

    public function deleteOne($comment)
    {
        $comment->delete();
        return ['message' => 'Comentário removido com sucesso.'];
    }
}
