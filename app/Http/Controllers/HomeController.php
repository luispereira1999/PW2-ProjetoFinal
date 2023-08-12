<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\PostService;

/**
 * Controlador responsável por tratar as operações relativas aos posts.
 */
class HomeController extends Controller
{
    protected $authService;
    protected $postService;

    /**
     * Construtor da classe PostController.
     *
     * @param   \App\Services\AuthService $authService          Instância de AuthService.
     * @param   \App\Services\PostService $postService          Instância de PostService.
     * @param   \App\Services\CommentService $commentService    Instância de CommentService.
     */
    public function __construct(AuthService $authService, PostService $postService)
    {
        $this->authService = $authService;
        $this->postService = $postService;
    }


    /**
     * Ir para a página inicial.
     *
     * @return  \Illuminate\Http\Response   A resposta HTTP.
     */
    public function home()
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
}
