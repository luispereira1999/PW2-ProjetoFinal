<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\CommentVoteService;
use App\Models\Comment;

/**
 * Esta classe trata das várias operações relativas aos comentários dos posts.
 */
class CommentService
{
    protected $commentVoteService;

    /**
     * Construtor da classe CommentService.
     *
     * @param \App\Services\CommentVoteService $commentVoteService  Instância de CommentVoteService.
     */
    public function __construct(CommentVoteService $commentVoteService)
    {
        $this->commentVoteService = $commentVoteService;
    }


    /**
     * Obter todos os comentários de um post.
     *
     * @param  int $postId  Identificador do post que possui os comentários.
     * @param  int $loggedUserId  Identificador do utilizador autenticado.
     * @return \App\Models\Comment[]  A lista de comentários.
     */
    public function getAllByPost($postId, $loggedUserId)
    {
        $comments = Comment::select([
            'comments.id',
            'comments.description',
            'comments.votes_amount',
            'comments.user_id               AS comment_user_id',
            'comments.post_id               AS comment_post_id',
            'users.name                     AS comment_user_name',
            'comments_votes.user_id         AS vote_user_id',
            'comments_votes.vote_type_id    AS vote_type_id',
        ])
            ->from('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('comments_votes', function ($join) use ($loggedUserId) {
                $join->on('comments.id', '=', 'comments_votes.comment_id')->where('comments_votes.user_id', '=', $loggedUserId);
            })
            ->where('comments.post_id', '=', $postId)
            ->orderByDesc('votes_amount')
            ->get();

        return $comments;
    }


    /**
     * Obter um comentário pelo identificador do comentário.
     *
     * @param  int $commentId  Identificador do comentário.
     * @return \App\Models\Comment  O comentário obtido.
     */
    public function getOne($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        return $comment;
    }


    /**
     * Obter a quantidade de votos de um comentário.
     *
     * @param  int $commentId  Identificador do comentário que possui os votos.
     * @return string  A quantidade de votos obtida.
     */
    public function getVotesAmount($commentId)
    {
        $votesAmount = Comment::where('id', $commentId)
            ->pluck('votes_amount')
            ->first();

        return $votesAmount;
    }


    /**
     * Inserir um novo comentário na base de dados.
     *
     * @param  string $description  Descrição do comentário.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a criar o comentário.
     * @param  int $postId  Identificador do post que pertence o comentário.
     * @return string  A mensagem de sucesso.
     */
    public function insertOne($description, $loggedUserId, $postId)
    {
        $comment = new Comment();
        $comment->description = $description;
        $comment->user_id = $loggedUserId;
        $comment->post_id = $postId;
        $comment->save();

        return 'Comentário criado com sucesso.';
    }


    /**
     * Atualizar um comentário.
     *
     * @param  \App\Models\Comment $comment  Objeto do modelo do comentário que será atualizado.
     * @param  string $description  Descrição do comentário.
     * @return string  A mensagem de sucesso.
     */
    public function updateOne($comment, $description)
    {
        $comment->description = $description;
        $comment->save();

        return 'Comentário atualizado com sucesso.';
    }


    /**
     * Atualizar a quantidade de votos de um comentário.
     *
     * @param  int $commentId  Identificador do comentário que será atualizado.
     * @param  int $votesAmount  Quantidade de votos a somar ou a subtrair da quantidade atual.
     * @return string  A mensagem de sucesso.
     */
    public function updateVotesAmount($commentId, $votesAmount)
    {
        $comment = $this->getOne($commentId);
        $comment->increment('votes_amount', $votesAmount);

        return 'Quantidade de votos do comentário atualizada com sucesso.';
    }


    /**
     * Votar num comentário de um post.
     *
     * @param  int $commentId  Identificador do comentário que será votado.
     * @param  int $loggedUserId  Identificador do utilizador autenticado que está a votar no comentário.
     * @param  int $voteTypeId  Identificador do tipo de voto (upvote ou downvote).
     * @return array  Um array associativo com o status da resposta e uma mensagem indicando o resultado da operação.
     */
    public function vote($commentId, $loggedUserId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            $commentVote = $this->commentVoteService->getOne($commentId, $loggedUserId);

            // se já existe algum voto do utilizador para este comentário
            if ($commentVote) {
                // se o utilizador está a votar no mesmo tipo de voto
                if ($voteTypeId == $commentVote->vote_type_id) {
                    $commentVote = $this->commentVoteService->deleteOne($commentId, $loggedUserId);

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {  // está a votar no tipo de voto contrário
                    $commentVote = $this->commentVoteService->updateOrInsertOne($commentId, $loggedUserId, $voteTypeId);

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {  // insere novo voto
                $commentVote = $this->commentVoteService->insertOne($commentId, $loggedUserId, $voteTypeId);

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


    /**
     * Remover um comentário.
     *
     * @param  \App\Models\Comment $comment  Objeto do modelo do comentário que será removido.
     * @return string  A mensagem de sucesso.
     */
    public function deleteOne($comment)
    {
        $comment->delete();
        return 'Comentário removido com sucesso.';
    }
}
