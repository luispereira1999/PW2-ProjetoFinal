<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;

class CheckPostBelongsUserMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Middleware para verificar se um post com um determinado id existe na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $post = $request->attributes->get('post');
        $loggedUserId = $this->authService->getUserId();

        if ($post->user_id != $loggedUserId) {
            return redirect('500')->with('errors', 'Post não pertence ao utilizador atualmente com login.');
        }

        return $next($request);
    }
}
