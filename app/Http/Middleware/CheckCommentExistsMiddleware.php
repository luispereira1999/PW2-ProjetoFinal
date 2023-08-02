<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Comment;

class CheckCommentExistsMiddleware
{
    /**
     * Middleware para verificar se um comentário com um determinado id existe na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $commentId = $request->route('commentId');

        $comment = Comment::find($commentId);

        if (!$comment) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['O comentário não foi encontrado.']
                ], 500);
            } else {
                return response()->view('500', [
                    'success' => false,
                    'errors' => ['O comentário não foi encontrado.']
                ], 500);
            }
        }

        // para acessar o comentário encontrado no objeto $request nos controladores
        $request->attributes->set('comment', $comment);

        return $next($request);
    }
}
