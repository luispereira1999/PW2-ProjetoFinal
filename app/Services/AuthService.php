<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class authService
{
    public function loginUser($user)
    {
        auth()->login($user);
        return ['message' => 'Utilizador efetuou login com sucesso.'];
    }


    public function getUser()
    {
        $user = Auth::user();
        return $user;
    }


    public function getUserId()
    {
        $user = Auth::user();

        if ($user) {
            return $user->id;
        } else {
            return -1;
        }
    }


    public function cleanSession($session)
    {
        $session->invalidate();
        $session->regenerateToken();
    }


    public function logoutUser()
    {
        auth()->logout();
        return ['success' => true, 'message' => 'Utilizador terminou sessão com sucesso.'];
    }
}
