<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Services\AuthService;
use App\Services\PostService;
use App\Services\CommentService;

class CommentController extends Controller
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
     * Criar um novo comentário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }


    /**
     * Atualizar um comentário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $commentId)
    {
        $request->validate([
            'description' => 'required',
        ], [
            'description.required' => 'A descrição é obrigatória.'
        ]);

        // obtido do middleware que verificar se o comentário existe
        $comment = $request->attributes->get('comment');

        $loggedUserId = $this->authService->getUserId();
        $result = $this->commentService->updateOne($comment, $loggedUserId, $request->input('title'), $request->input('description'));

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }


    /**
     * Atualizar um voto de um comentário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function vote(Request $request, $commentId)
    {
        $request->validate([
            'voteTypeId' => 'required|in:1,2'
        ], [
            'voteTypeId.required' => 'O identificador do tipo de voto é obrigatório.',
            'voteTypeId.in' => 'O identificador do tipo de voto inválido.',
        ]);

        $loggedUserId = $this->authService->getUserId();

        $this->commentService->vote($commentId, $loggedUserId, $request->input('voteTypeId'));
        $votesAmount = $this->commentService->getVotesAmount($commentId);

        return response()->json(['votesAmount' => $votesAmount]);
    }


    /**
     * Remover um comentário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $commentId)
    {
        // obtido do middleware que verificar se o comentário existe
        $comment = $request->attributes->get('comment');

        $loggedUserId = $this->authService->getUserId();
        $result = $this->commentService->delete($comment, $loggedUserId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
