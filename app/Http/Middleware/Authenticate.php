<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Middleware para verificar se o utilizador está autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['Você precisa fazer login para atualizar seu perfil.']
            ], 500);
        }
    }
}
