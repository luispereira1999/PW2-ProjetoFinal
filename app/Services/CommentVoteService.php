<?php

namespace App\Services;

use App\Models\CommentVote;

/**
 * Esta classe trata das várias operações relativas aos votos dos comentários.
 */
class CommentVoteService
{
    /**
     * Obter um voto do comentário pelo identificador do comentário e identificador do utilizador autenticado.
     *
     * @param  int $commentId  Identificador do comentário.
     * @param  int $userId  Identificador do utilizador autenticado.
     * @return \App\Models\CommentVote  O voto do comentário obtido.
     */
    public function getOne($commentId, $userId)
    {
        $commentVote = CommentVote::select(
            'comment_id',
            'user_id',
            'vote_type_id'
        )
            ->where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        return $commentVote;
    }


    /**
     * Inserir um novo voto de um comentário na base de dados.
     *
     * @param  int $commentId  Identificador do comentário que pertencerá o voto.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no comentário.
     * @param  int $voteTypeId  Identificador do tipo de voto (upvote ou downvote).
     * @return string  A mensagem de sucesso.
     */
    public function insertOne($commentId, $loggedUserId, $voteTypeId)
    {
        $commentVote = new CommentVote();
        $commentVote->comment_id = $commentId;
        $commentVote->user_id = $loggedUserId;
        $commentVote->vote_type_id = $voteTypeId;
        $commentVote->save();

        return 'Comentário votado com sucesso.';
    }


    /**
     * Atualizar ou inserir um novo voto de um comentário na base de dados.
     *
     * @param  int $commentId  Identificador do comentário que pertencerá o voto.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no comentário.
     * @param  int $voteTypeId  Identificador do tipo de voto (upvote ou downvote).
     * @return string  A mensagem de sucesso.
     */
    public function updateOrInsertOne($commentId, $loggedUserId, $voteTypeId)
    {
        CommentVote::updateOrInsert(
            ['comment_id' => $commentId, 'user_id' => $loggedUserId],
            ['vote_type_id' => $voteTypeId]
        );

        return 'Comentário votado com sucesso.';
    }


    /**
     * Remover um voto de um comentário.
     *
     * @param  \App\Models\CommentVote $commentId  Objeto do modelo do voto do comentário que será removido.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no comentário.
     * @return string  A mensagem de sucesso.
     */
    public function deleteOne($commentId, $loggedUserId)
    {
        CommentVote::where('comment_id', $commentId)
            ->where('user_id', $loggedUserId)
            ->delete();

        return 'Voto do comentário removido com sucesso.';
    }
}
