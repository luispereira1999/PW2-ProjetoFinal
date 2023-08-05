<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\PostVoteService;
use App\Models\Post;

/**
 * Esta classe trata das várias operações relativas aos posts.
 */
class PostService
{
    protected $postVoteService;

    /**
     * Construtor da classe PostService.
     *
     * @param   \App\Services\PostVoteService $postVoteService  Instância de PostVoteService.
     */
    public function __construct(PostVoteService $postVoteService)
    {
        $this->postVoteService = $postVoteService;
    }


    /**
     * Obter todos os posts na base de dados.
     *
     * @param   int $loggedUserId   Identificador do utilizador autenticado.
     * @return  \App\Models\Post[]  A lista de posts.
     */
    public function getAll($loggedUserId)
    {
        $posts = Post::select([
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
        ])
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($loggedUserId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->orderByDesc('posts.date')
            ->get();

        return $posts;
    }


    /**
     * Obter todos os posts de um utilizador.
     *
     * @param   int $userId         Identificador do utilizador que possui os posts.
     * @param   int $loggedUserId   Identificador do utilizador autenticado.
     * @return  \App\Models\Post[]  A lista de posts.
     */
    public function getAllByUser($userId, $loggedUserId)
    {
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


    /**
     * Obter todos os posts com um determinado título.
     *
     * @param   string $title       Texto do título do post.
     * @param   int $loggedUserId   Identificador do utilizador autenticado.
     * @return  \App\Models\Post[]  A lista de posts.
     */
    public function getAllByTitle($title, $loggedUserId)
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
            ->leftJoin('posts_votes', function ($join) use ($loggedUserId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->whereRaw('title LIKE BINARY ?', [$titleFilter])
            ->orderByDesc('posts.date')
            ->get();

        return $posts;
    }


    /**
     * Obter um post pelo identificador do post.
     *
     * @param   int $postId         Identificador do post.
     * @return  \App\Models\Post    O post obtido.
     */
    public function getOne($postId)
    {
        $post = Post::findOrFail($postId);
        return $post;
    }


    /**
     * Obter um post pelo identificador do post, incluindo os votos do post.
     *
     * @param   int $postId         Identificador do post.
     * @param   int $loggedUserId   Identificador do utilizador autenticado.
     * @return  \App\Models\Post    O post obtido.
     */
    public function getOneWithVotes($postId, $loggedUserId)
    {
        $post = Post::select(
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
            ->leftJoin('posts_votes', function ($query) use ($loggedUserId) {
                $query->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->where('posts.id', '=', $postId)
            ->first();

        return $post;
    }


    /**
     * Obter o post com mais votos.
     *
     * @param   int $loggedUserId   Identificador do utilizador autenticado.
     * @return  \App\Models\Post    O post obtido.
     */
    public function getOneByMostVotes($loggedUserId)
    {
        $post = Post::select([
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
        ])
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('posts_votes', function ($join) use ($loggedUserId) {
                $join->on('posts.id', '=', 'posts_votes.post_id')
                    ->where('posts_votes.user_id', '=', $loggedUserId);
            })
            ->orderByDesc('posts.votes_amount')
            ->first();

        return $post;
    }


    /**
     * Obter a quantidade de votos de um post.
     *
     * @param   int $commentId  Identificador do post que possui os votos.
     * @return  string          A quantidade de votos obtida.
     */
    public function getVotesAmount($postId)
    {
        $votesAmount = Post::where('id', $postId)
            ->pluck('votes_amount')
            ->first();

        return $votesAmount;
    }


    /**
     * Inserir um novo post na base de dados.
     *
     * @param   string $title                       Título do post.
     * @param   string $description                 Descrição do post.
     * @param   \Illuminate\Support\Carbon $date    Data atual (agora).
     * @param   int $loggedUserId                   Identificador do utilizador autenticado que está a criar o post.
     * @return  string                              A mensagem de sucesso.
     */
    public function insertOne($title, $description, $date, $loggedUserId)
    {
        $post = new Post();
        $post->title = $title;
        $post->description = $description;
        $post->date = $date;
        $post->user_id = $loggedUserId;
        $post->save();

        return 'Post criado com sucesso.';
    }


    /**
     * Atualizar um post.
     *
     * @param   \App\Models\Post $post  Objeto do modelo do post que será atualizado.
     * @param   string $title           Título do post.
     * @param   string $description     Descrição do post.
     * @return  string                  A mensagem de sucesso.
     */
    public function updateOne($post, $title, $description)
    {
        $post->title = $title;
        $post->description = $description;
        $post->save();

        return 'Post atualizado com sucesso.';
    }


    /**
     * Atualizar a quantidade de votos de um post.
     *
     * @param   int $postId         Identificador do post que será atualizado.
     * @param   int $votesAmount    Quantidade de votos a somar ou a subtrair da quantidade atual.
     * @return  string              A mensagem de sucesso.
     */
    public function updateVotesAmount($postId, $votesAmount)
    {
        $post = $this->getOne($postId);
        $post->increment('votes_amount', $votesAmount);

        return 'Quantidade de votos do post atualizada com sucesso.';
    }


    /**
     * Atualizar a quantidade de comentários de um post.
     *
     * @param   int $postId             Identificador do post que será atualizado.
     * @param   int $commentsAmount     Quantidade de comentários a somar ou a subtrair da quantidade atual.
     * @return  string                  A mensagem de sucesso.
     */
    public function updateCommentsAmount($postId, $commentsAmount)
    {
        $post = $this->getOne($postId);
        $post->increment('comments_amount', $commentsAmount);

        return 'Quantidade de comentários do post atualizada com sucesso.';
    }


    /**
     * Votar num post.
     *
     * @param   int $postId         Identificador do post que será votado.
     * @param   int $loggedUserId   Identificador do utilizador autenticado que está a votar no post.
     * @param   int $voteTypeId     Identificador do tipo de voto (upvote ou downvote).
     * @return  array               O array associativo com o status da resposta e uma mensagem indicando o resultado da operação.
     */
    public function vote($postId, $loggedUserId, $voteTypeId)
    {
        try {
            DB::beginTransaction();

            $postVote = $this->postVoteService->getOne($postId, $loggedUserId);

            // se já existe algum voto do utilizador para este post
            if ($postVote) {
                // se o utilizador está a votar no mesmo tipo de voto
                if ($voteTypeId == $postVote->vote_type_id) {
                    $postVote = $this->postVoteService->deleteOne($postId, $loggedUserId);

                    if ($voteTypeId == 1) {
                        $votesAmount = -1;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = 1;
                    }
                } else {  // está a votar no tipo de voto contrário
                    $postVote = $this->postVoteService->updateOrInsertOne($postId, $loggedUserId, $voteTypeId);

                    if ($voteTypeId == 1) {
                        $votesAmount = 2;
                    } else if ($voteTypeId == 2) {
                        $votesAmount = -2;
                    }
                }
            } else {  // insere novo voto
                $postVote = $this->postVoteService->insertOne($postId, $loggedUserId, $voteTypeId);

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


    /**
     * Remover um post.
     *
     * @param   \App\Models\Post $post  Objeto do modelo do post que será removido.
     * @return  string                  A mensagem de sucesso.
     */
    public function deleteOne($post)
    {
        $post->delete();
        return 'Post removido com sucesso.';
    }
}
