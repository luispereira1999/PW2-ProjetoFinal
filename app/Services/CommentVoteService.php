<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CommentVoteService
{
    public function getOne($commentId, $userId)
    {
        $commentVote = DB::table('comments_votes')
            ->select('comment_id', 'user_id', 'vote_type_id')
            ->where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        return $commentVote;
    }


    public function insertOne($commentId, $userId, $voteTypeId)
    {
        DB::table('comments_votes')
            ->insert([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'vote_type_id' => $voteTypeId,
            ]);

        return 'Voto do comentário criado com sucesso.';
    }


    public function updateOrInsertOne($commentId, $userId, $voteTypeId)
    {
        DB::table('comments_votes')
            ->updateOrInsert(
                ['comment_id' => $commentId, 'user_id' => $userId],
                ['vote_type_id' => $voteTypeId]
            );

        return 'Voto do comentário atualizado com sucesso.';
    }


    public function deleteOne($commentId, $userId)
    {
        DB::table('comments_votes')
            ->where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->delete();

        return 'Voto do comentário removido com sucesso.';
    }
}
