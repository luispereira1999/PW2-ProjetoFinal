<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Post;

class CheckPostExistsMiddleware
{
    /**
     * Middleware para verificar se um post com um id existe na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('postId');

        $post = Post::find($postId);

        if (!$post) {
            return redirect()->back()->with('error', 'Post não encontrado.');
        }

        // para acessar o post encontrado no objeto $request nos controladores
        $request->attributes->set('post', $post);

        return $next($request);
    }
}
