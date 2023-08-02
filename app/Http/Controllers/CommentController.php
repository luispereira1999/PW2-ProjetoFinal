<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $authService;
    protected $commentService;

    public function __construct(AuthService $authService, CommentService $commentService)
    {
        $this->authService = $authService;
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
        $data = $request->validate([
            'description' => 'required|min:1|max:500'
        ], [
            'description.required' => 'A descrição é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max' => 'A descrição não pode ter mais de :max caracteres.'
        ]);

        // obtido do middleware que verifica se o post existe
        $post = $request->attributes->get('post');

        $loggedUserId = $this->authService->getUserId();
        $result = $this->commentService->insertOne($data['description'], $loggedUserId, $post->id);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $result['message']
        ], 201);
    }


    /**
     * Atualizar um comentário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|min:1|max:500'
        ], [
            'description.required' => 'A descrição é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max' => 'A descrição não pode ter mais de :max caracteres.'
        ]);

        // obtido do middleware que verifica se o comentário existe
        $comment = $request->attributes->get('comment');

        $result = $this->commentService->updateOne($comment, $data['description']);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $result['message']
        ], 200);
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
    public function destroy(Request $request)
    {
        // obtido do middleware que verifica se o comentário existe
        $comment = $request->attributes->get('comment');

        $result = $this->commentService->delete($comment);

        return back()->with([
            'success' => true,
            'errors' => [],
            'message' => $result['message']
        ], 200);
    }
}
