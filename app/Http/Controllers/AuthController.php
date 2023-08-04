<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;

/**
 * Controlador responsável por tratar as operações relativas à autenticação de utilizadores.
 */
class AuthController extends Controller
{
    protected $authService;
    protected $userService;


    /**
     * Construtor da classe AuthController.
     *
     * @param  \App\Services\AuthService $authService  Instância de AuthService.
     * @param  \App\Services\UserService $userService  Instância de UserService.
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }


    /**
     * Ir para a página de autenticação.
     *
     * @return \Illuminate\Http\Response  A resposta HTTP.
     */
    public function index()
    {
        return view('auth');
    }


    /**
     * Iniciar sessão de um utilizador.
     *
     * @param  \Illuminate\Http\Request $request  Requisição HTTP.
     * @return \Illuminate\Http\Response  A resposta HTTP.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name_or_email' => ['required'],
            'password' => ['required']
        ], [
            'name_or_email.required' => 'O nome de utilizador ou palavra-passe é obrigatório.',
            'password.required' => 'A palavra-passe é obrigatória.'
        ]);

        $result = $this->authService->loginByCredentials($credentials, $request->session());

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'errors' => [$result['message']]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => 'Login efetuado com sucesso.'
        ], 200);
    }


    /**
     * Registar um utilizador.
     *
     * @param  \Illuminate\Http\Request $request  Requisição HTTP.
     * @return \Illuminate\Http\Response  A resposta HTTP.
     */
    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:12|UniqueNameOrEmail',
            'email' => 'required|email|max:200|UniqueNameOrEmail',
            'password' => 'required|confirmed|min:6|max:12'
        ], [
            'name.required' => 'O nome de utilizador é obrigatório.',
            'name.min' => 'O nome de utilizador deve ter pelo menos :min caracteres.',
            'name.max' => 'O nome de utilizador não pode ter mais de :max caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email é inválido.',
            'email.max' => 'O email não pode ter mais de :max caracteres.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.confirmed' => 'As palavra-passes não coincidem.',
            'password.min' => 'A palavra-passe deve ter pelo menos :min caracteres.',
            'password.max' => 'A palavra-passe não pode ter mais de :max caracteres.'
        ]);

        $user = $this->userService->insertOne($data['name'], $data['password'], $data['email']);
        $message = $this->authService->loginByObject($user, $request->session());

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 201);
    }


    /**
     * Terminar sessão de um utilizador.
     *
     * @param  \Illuminate\Http\Request $request  Requisição HTTP.
     * @return \Illuminate\Http\Response  A resposta HTTP.
     */
    public function logout(Request $request)
    {
        $message = $this->authService->logoutUser($request->session());

        return redirect('/')->with([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }
}
