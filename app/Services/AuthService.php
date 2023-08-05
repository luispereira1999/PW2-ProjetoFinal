<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Esta classe trata das várias operações relativas à autenticação de utilizadores.
 */
class AuthService
{
    /**
     * Obter o utilizador autenticado.
     *
     * @return  \App\Models\User|null   O objeto do modelo do utilizador autenticado ou null se não houver nenhum utilizador autenticado.
     */
    public function getUser()
    {
        $user = Auth::user();
        return $user;
    }


    /**
     * Obter o identificador do utilizador autenticado.
     *
     * @return  int     O identificador do utilizador autenticado ou -1 se não houver nenhum utilizador autenticado.
     */
    public function getUserId()
    {
        $user = Auth::user();

        if ($user) {
            return $user->id;
        } else {
            return -1;
        }
    }


    /**
     * Verificar se a palavra-passe fornecida corresponde à palavra-passe armazenada no hash da base de dados.
     *
     * @param   string $inputPassword   Palavra-passe fornecida pelo utilizador.
     * @param   string $hashedPassword  Hash da palavra-passe armazenada.
     * @return  array                   O array associativo com o status da resposta e uma mensagem de erro em caso de erro.
     */
    public function checkPassword($inputPassword, $hashedPassword)
    {
        if (!Hash::check($inputPassword, $hashedPassword)) {
            return ['success' => false, 'message' => 'A palavra-passe atual fornecida está incorreta.'];
        } else {
            return ['success' => true];
        }
    }


    /**
     * Fazer login de um utilizador através das credenciais fornecidas.
     *
     * @param   array $credentials                              Array associativo com as credenciais de acesso (nome de utilizador/email e palavra-passe).
     * @param   \Illuminate\Contracts\Session\Session $session  Sessão do utilizador.
     * @return  array                                           O array associativo com o status da resposta e uma mensagem de erro em caso de erro.
     *
     */
    public function loginByCredentials($credentials, $session)
    {
        $user = User::where(function ($query) use ($credentials) {
            $query->where('name', $credentials['name_or_email'])
                ->orWhere('email', $credentials['name_or_email']);
        })->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ['success' => false, 'message' => 'Credenciais de acesso inválidas.'];
        }

        Auth::login($user);

        $session->regenerate();

        return ['success' => true];
    }


    /**
     * Fazer login de um utilizador através do objeto do modelo do utilizador fornecido.
     *
     * @param   \App\Models\User $user                          Objeto do modelo do utilizador que está a fazer login.
     * @param   \Illuminate\Contracts\Session\Session $session  Sessão do utilizador.
     * @return  string                                          A mensagem de sucesso.
     */
    public function loginByObject($user, $session)
    {
        auth()->login($user);

        $session->regenerate();

        return 'Utilizador efetuou login com sucesso.';
    }


    /**
     * Terminar a sessão de um utilizador.
     *
     * @param   \Illuminate\Contracts\Session\Session $session  Sessão do utilizador.
     * @return  string                                          A mensagem de sucesso.
     */
    public function logoutUser($session)
    {
        $session->invalidate();
        $session->regenerateToken();

        auth()->logout();

        return 'Utilizador terminou sessão com sucesso.';
    }
}
