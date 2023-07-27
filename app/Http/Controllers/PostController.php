<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\PostService;
use App\Services\CommentService;

class PostController extends Controller
{
    protected $authService;
    protected $postService;
    protected $commentService;

    public function __construct(AuthService $authService, PostService $postService, CommentService $commentService)
    {
        $this->authService = $authService;
        $this->postService = $postService;
        $this->commentService = $commentService;
    }


    /**
     * Mostrar todos os posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggedUserId = $this->authService->getUserId();
        $posts = $this->postService->getAll($loggedUserId);

        return view('home', [
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ]);
    }


    /**
     * Criar um novo post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $loggedUserId = $this->authService->getUserId();
        $result = $this->postService->insertOne($request->input('title'), $request->input('description'), $loggedUserId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }


    /**
     * Mostrar um post específico.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function show($postId)
    {
        $loggedUserId = $this->authService->getUserId();
        $post = $this->postService->getOneWithUserVote($postId, $loggedUserId);
        $comments = $this->commentService->getAllByPostId($postId, $loggedUserId);

        return view('post', [
            'loggedUserId' => $loggedUserId,
            'post' => $post,
            'comments' => $comments,
        ]);
    }


    /**
     * Atualizar um post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $postId)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'O título é obrigatório.',
            'description.required' => 'A descrição é obrigatória.'
        ]);

        // obtido do middleware que verificar se o post existe
        $post = $request->attributes->get('post');

        $loggedUserId = $this->authService->getUserId();
        $result = $this->postService->updateOne($post, $loggedUserId, $request->input('title'), $request->input('description'));

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }


    /**
     * Atualizar um voto de um post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function vote(Request $request, $postId)
    {
        $request->validate([
            'voteTypeId' => 'required|in:1,2'
        ], [
            'voteTypeId.required' => 'O identificador do tipo de voto é obrigatório.',
            'voteTypeId.in' => 'O identificador do tipo de voto inválido.',
        ]);

        $loggedUserId = $this->authService->getUserId();

        $this->postService->vote($postId, $loggedUserId, $request->input('voteTypeId'));
        $votesAmount = $this->postService->getVotesAmount($postId);

        return response()->json(['votesAmount' => $votesAmount]);
    }


    /**
     * Remover um post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $postId)
    {
        // obtido do middleware que verificar se o post existe
        $post = $request->attributes->get('post');

        $loggedUserId = $this->authService->getUserId();
        $result = $this->postService->delete($post, $loggedUserId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
