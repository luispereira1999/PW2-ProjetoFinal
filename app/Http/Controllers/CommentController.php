<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\CommentService;

/**
 * Controlador responsável por tratar as operações relativas aos comentários.
 */
class CommentController extends Controller
{
    protected $authService;
    protected $commentService;

    /**
     * Construtor da classe CommentController.
     *
     * @param   \App\Services\AuthService $authService           Instância de AuthService.
     * @param   \App\Services\CommentService $commentService     Instância de CommentService.
     */
    public function __construct(AuthService $authService, CommentService $commentService)
    {
        $this->authService = $authService;
        $this->commentService = $commentService;
    }


    /**
     * Criar um novo comentário.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
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

        if (!$result['success']) {
            return redirect('500')->with([
                'success' => false,
                'errors' => [$result['message']]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $result['message']
        ], 201);
    }


    /**
     * Atualizar um comentário.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function edit(Request $request)
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

        $message = $this->commentService->updateOne($comment, $data['description']);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }


    /**
     * Atualizar um voto de um comentário.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @param   int $commentId                      Identificador do comentário.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function vote(Request $request, $commentId)
    {
        $data = $request->validate([
            'voteTypeId' => 'required|in:1,2'
        ], [
            'voteTypeId.required' => 'O identificador do tipo de voto é obrigatório.',
            'voteTypeId.in' => 'O identificador do tipo de voto inválido.',
        ]);

        $loggedUserId = $this->authService->getUserId();
        $result = $this->commentService->vote($commentId, $loggedUserId, $data['voteTypeId']);
        $votesAmount = $this->commentService->getVotesAmount($commentId);

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
     * Remover um comentário.
     *
     * @param   \Illuminate\Http\Request $request   Requisição HTTP.
     * @return  \Illuminate\Http\Response           A resposta HTTP.
     */
    public function delete(Request $request)
    {
        // obtido do middleware que verifica se o post existe
        $post = $request->attributes->get('post');

        // obtido do middleware que verifica se o comentário existe
        $comment = $request->attributes->get('comment');

        $result = $this->commentService->deleteOne($comment, $post->id);

        if (!$result['success']) {
            return redirect('500')->with([
                'success' => false,
                'errors' => [$result['message']]
            ], 500);
        }

        return back()->with([
            'success' => true,
            'message' => $result['message']
        ], 200);
    }
}
