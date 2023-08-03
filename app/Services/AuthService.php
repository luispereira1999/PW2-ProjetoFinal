<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authService
{
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


    public function checkPassword($inputPassword, $hashedPassword)
    {
        if (!Hash::check($inputPassword, $hashedPassword)) {
            return ['success' => false, 'message' => 'A palavra-passe atual fornecida está incorreta.'];
        } else {
            return ['success' => true];
        }
    }


    public function loginByCredentials($credentials, $session)
    {
        if (!Auth::attempt($credentials)) {
            return ['success' => false, 'message' => 'Credenciais de acesso inválidas.'];
        } else {
            $session->regenerate();
            return ['success' => true];
        }
    }


    public function loginByObject($user, $session)
    {
        auth()->login($user);

        $session->regenerate();

        return ['message' => 'Utilizador efetuou login com sucesso.'];
    }


    public function logoutUser($session)
    {
        $session->invalidate();
        $session->regenerateToken();

        auth()->logout();

        return 'Utilizador terminou sessão com sucesso.';
    }
}
