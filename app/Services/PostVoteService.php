<?php

namespace App\Services;

use App\Models\PostVote;

/**
 * Esta classe trata das várias operações relativas aos votos dos posts.
 */
class PostVoteService
{
    /**
     * Obter um voto do post pelo identificador do post e identificador do utilizador autenticado.
     *
     * @param  int $postId  Identificador do post.
     * @param  int $userId  Identificador do utilizador autenticado.
     * @return \App\Models\PostVote  O voto do post obtido.
     */
    public function getOne($postId, $loggedUserId)
    {
        $postVote = PostVote::select(
            'post_id',
            'user_id',
            'vote_type_id'
        )
            ->where('post_id', $postId)
            ->where('user_id', $loggedUserId)
            ->first();

        return $postVote;
    }


    /**
     * Inserir um novo voto de um post na base de dados.
     *
     * @param  int $postId  Identificador do post que pertencerá o voto.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no post.
     * @param  int $voteTypeId  Identificador do tipo de voto (upvote ou downvote).
     * @return string  A mensagem de sucesso.
     */
    public function insertOne($postId, $loggedUserId, $voteTypeId)
    {
        $postVote = new PostVote();
        $postVote->post_id = $postId;
        $postVote->user_id = $loggedUserId;
        $postVote->vote_type_id = $voteTypeId;
        $postVote->save();

        return 'Post votado com sucesso.';
    }


    /**
     * Atualizar ou inserir um novo voto de um post na base de dados.
     *
     * @param  int $commentId  Identificador do post que pertencerá o voto.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no post.
     * @param  int $voteTypeId  Identificador do tipo de voto (upvote ou downvote).
     * @return string  A mensagem de sucesso.
     */
    public function updateOrInsertOne($postId, $loggedUserId, $voteTypeId)
    {
        PostVote::updateOrInsert(
            ['post_id' => $postId, 'user_id' => $loggedUserId],
            ['vote_type_id' => $voteTypeId]
        );

        return 'Post votado com sucesso.';
    }


    /**
     * Remover um voto de um post.
     *
     * @param  \App\Models\PostVote $postId  Objeto do modelo do voto do post que será removido.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no post.
     * @return string  A mensagem de sucesso.
     */
    public function deleteOne($postId, $loggedUserId)
    {
        PostVote::where('post_id', $postId)
            ->where('user_id', $loggedUserId)
            ->delete();

        return 'Voto do post removido com sucesso.';
    }
}
