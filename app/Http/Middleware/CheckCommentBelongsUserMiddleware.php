<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;

class CheckCommentBelongsUserMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Middleware para verificar se um comentário pertence ao utilizador atualmente com login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $comment = $request->attributes->get('comment');
        $loggedUserId = $this->authService->getUserId();

        if ($comment->user_id != $loggedUserId) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['O comentário não pertence ao utilizador atualmente com login.']
                ], 500);
            } else {
                return response()->view('500', [
                    'success' => false,
                    'errors' => ['O comentário não pertence ao utilizador atualmente com login.']
                ], 500);
            }
        }

        return $next($request);
    }
}
