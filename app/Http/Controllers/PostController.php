<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\PostService;
use App\Services\CommentService;

/**
 * Controlador responsável por tratar as operações relativas aos posts.
 */
class PostController extends Controller
{
    protected $authService;
    protected $postService;
    protected $commentService;

    /**
     * Construtor da classe PostController.
     *
     * @param   \App\Services\AuthService $authService          Instância de AuthService.
     * @param   \App\Services\PostService $postService          Instância de PostService.
     * @param   \App\Services\CommentService $commentService    Instância de CommentService.
     */
    public function __construct(AuthService $authService, PostService $postService, CommentService $commentService)
    {
        $this->authService = $authService;
        $this->postService = $postService;
        $this->commentService = $commentService;
    }


    /**
     * Ir para a página inicial.
     *
     * @return  \Illuminate\Http\Response   A resposta HTTP.
     */
    public function index()
    {
        $loggedUserId = $this->authService->getUserId();
        $featuredPost = $this->postService->getOneByMostVotes($loggedUserId);
        $posts = $this->postService->getAll($loggedUserId);

        return response()->view('home', [
            'featuredPost' => $featuredPost,
            'searchText' => '',
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ], 200);
    }


    /**
     * Pesquisar posts pelo título na página inicial.
     *
     * @param   string $searchText                  Texto da pesquisa.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function search($searchText)
    {
        $loggedUserId = $this->authService->getUserId();
        $featuredPost = $this->postService->getOneByMostVotes($loggedUserId);
        $posts = $this->postService->getAllByTitle($searchText, $loggedUserId);

        return response()->view('home', [
            'featuredPost' => $featuredPost,
            'searchText' => $searchText,
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ], 200);
    }


    /**
     * Ir para a página de um post específico.
     *
     * @param   int $postId  Identificador do post.
     * @return  \Illuminate\Http\Response  A resposta HTTP.
     */
    public function show($postId)
    {
        $loggedUserId = $this->authService->getUserId();
        $post = $this->postService->getOneWithVotes($postId, $loggedUserId);
        $comments = $this->commentService->getAllByPost($postId, $loggedUserId);

        return response()->view('post', [
            'loggedUserId' => $loggedUserId,
            'post' => $post,
            'comments' => $comments,
        ], 200);
    }


    /**
     * Criar um novo post.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:1|max:50',
            'description' => 'required|min:1|max:2000'
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.min' => 'O título deve ter pelo menos :min caracteres.',
            'title.max' => 'O título não pode ter mais de :max caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max' => 'A descrição não pode ter mais de :max caracteres.'
        ]);

        $loggedUserId = $this->authService->getUserId();
        $message = $this->postService->insertOne($data['title'], $data['description'], now(), $loggedUserId);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 201);
    }


    /**
     * Atualizar um post.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:1|max:50',
            'description' => 'required|min:1|max:2000'
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.min' => 'O título deve ter pelo menos :min caracteres.',
            'title.max' => 'O título não pode ter mais de :max caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max' => 'A descrição não pode ter mais de :max caracteres.'
        ]);

        // obtido do middleware que verifica se o post existe
        $post = $request->attributes->get('post');

        $message = $this->postService->updateOne($post,  $data['title'], $data['description']);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }


    /**
     * Atualizar um voto de um post.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @param   int $postId                         Identificador do post.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function vote(Request $request, $postId)
    {
        $data = $request->validate([
            'voteTypeId' => 'required|in:1,2'
        ], [
            'voteTypeId.required' => 'O identificador do tipo de voto é obrigatório.',
            'voteTypeId.in' => 'O identificador do tipo de voto é inválido.',
        ]);

        $loggedUserId = $this->authService->getUserId();
        $result = $this->postService->vote($postId, $loggedUserId, $data['voteTypeId']);
        $votesAmount = $this->postService->getVotesAmount($postId);

        if (!$result['success']) {
            return redirect('500')->with([
                'success' => false,
                'errors' => [$result['message']]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => ['votesAmount' => $votesAmount],
            'errors' => [],
            'message' => $result['message']
        ], 200);
    }


    /**
     * Remover um post.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function destroy(Request $request)
    {
        // obtido do middleware que verifica se o post existe
        $post = $request->attributes->get('post');

        $message = $this->postService->deleteOne($post);

        return back()->with([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
