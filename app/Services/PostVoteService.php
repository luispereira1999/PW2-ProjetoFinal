<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PostVoteService
{
    public function getOne($postId, $userId)
    {
        $postVote = DB::table('posts_votes')
            ->select('post_id', 'user_id', 'vote_type_id')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        return $postVote;
    }


    public function insertOne($postId, $userId, $voteTypeId)
    {
        DB::table('posts_votes')
            ->insert([
                'post_id' => $postId,
                'user_id' => $userId,
                'vote_type_id' => $voteTypeId,
            ]);

        return 'Voto do post criado com sucesso.';
    }


    public function updateOrInsertOne($postId, $userId, $voteTypeId)
    {
        DB::table('posts_votes')
            ->updateOrInsert(
                ['post_id' => $postId, 'user_id' => $userId],
                ['vote_type_id' => $voteTypeId]
            );

        return 'Voto do post atualizado com sucesso.';
    }


    public function deleteOne($postId, $userId)
    {
        DB::table('posts_votes')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->delete();

        return 'Voto do post removido com sucesso.';
    }
}
