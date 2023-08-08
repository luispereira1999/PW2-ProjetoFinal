<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\PostService;

/**
 * Controlador responsável por tratar as operações relativas aos utilizadores.
 */
class UserController extends Controller
{
    protected $authService;
    protected $userService;
    protected $postService;


    /**
     * Construtor da classe UserController.
     *
     * @param   \App\Services\AuthService $authService   Instância de AuthService.
     * @param   \App\Services\UserService $userService   Instância de UserService.
     * @param   \App\Services\PostService $postService   Instância de PostService.
     */
    public function __construct(AuthService $authService, UserService $userService, PostService $postService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->postService = $postService;
    }


    /**
     * Ir para a página do perfil do utilizador.
     *
     * @param   int $userId  Identificador do utilizador.
     * @return  \Illuminate\Http\Response  A resposta HTTP.
     */
    public function index($userId)
    {
        $userProfile = $this->userService->getOne($userId);
        $loggedUserId = $this->authService->getUserId();
        $posts = $this->postService->getAllByUser($userId, $loggedUserId);

        return response()->view('profile', [
            'user' => $userProfile,
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ], 200);
    }


    /**
     * Ir para a página de definições da conta.
     *
     * @param   int $userId                  Identificador do utilizador.
     * @return  \Illuminate\Http\Response    A resposta HTTP.
     */
    public function show($userId)
    {
        $loggedUserId = $this->authService->getUserId();

        if ($loggedUserId != $userId) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $user = $this->userService->getOne($userId);

        return response()->view('account', [
            'user' => $user
        ], 200);
    }


    /**
     * Atualizar os dados básicos do utilizador com login.
     *
     * @param   \Illuminate\Http\Request $request    Requisição HTTP.
     * @param   int $userId                          Identificador do utilizador.
     * @return  \Illuminate\Http\Response            A resposta HTTP.
     */
    public function updateData(Request $request, $userId)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:15|UniqueNameOrEmail',
            'email' => 'required|email|max:200|UniqueNameOrEmail',
            'first_name' => 'max:50',
            'last_name' => 'max:50',
            'city' => 'max:30',
            'country' => 'max:100'
        ], [
            'name.required' => 'O nome de utilizador é obrigatório.',
            'name.min' => 'O nome de utilizador deve ter pelo menos :min caracteres.',
            'name.max' => 'O nome de utilizador não pode ter mais de :max caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email é inválido.',
            'email.max' => 'O email não pode ter mais de :max caracteres.',
            'first_name.max' => 'O primeiro nome não pode ter mais de :max caracteres.',
            'last_name.max' => 'O último nome não pode ter mais de :max caracteres.',
            'city.max' => 'A cidade não pode ter mais de :max caracteres.',
            'country.max' => 'O país não pode ter mais de :max caracteres.'
        ]);

        $loggedUserId = $this->authService->getUserId();

        if ($loggedUserId != $userId) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $user = $this->userService->getOne($userId);

        $message = $this->userService->updateDataOne(
            $user,
            $data['name'],
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['city'],
            $data['country']
        );

        $this->authService->loginByObject($user, $request->session());

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }


    /**
     * Atualizar a palavra-passe do utilizador com login.
     *
     * @param   \Illuminate\Http\Request $request    Requisição HTTP.
     * @param   int $userId                          Identificador do utilizador.
     * @return  \Illuminate\Http\Response            A resposta HTTP.
     */
    public function updatePassword(Request $request, $userId)
    {
        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|max:12',
            'new_password_confirm' => 'same:new_password'
        ], [
            'current_password.required' => 'A palavra-passe atual é obrigatória.',
            'new_password.min' => 'A nova palavra-passe deve ter pelo menos :min caracteres.',
            'new_password.max' => 'A nova palavra-passe não pode ter mais de :max caracteres.',
            'new_password.required' => 'A nova palavra-passe é obrigatória.',
            'new_password_confirm.same' => 'As palavra-passes não coincidem.'
        ]);

        $loggedUserId = $this->authService->getUserId();

        if ($loggedUserId != $userId) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $user = $this->userService->getOne($userId);
        $result = $this->authService->checkPassword($data['current_password'], $user->password);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'errors' => [$result['message']]
            ], 422);
        }

        $message = $this->userService->updatePasswordOne($user, $data['new_password']);
        $this->authService->loginByObject($user, $request->session());

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }


    /**
     * Remover o utilizador com login.
     *
     * @param   int $userId                  Identificador do utilizador.
     * @return  \Illuminate\Http\Response    A resposta HTTP.
     */
    public function destroy($userId)
    {
        $loggedUser = $this->authService->getUser();

        if ($loggedUser->id != $userId) {
            return response()->view('500', [
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $message = $this->userService->deleteOne($loggedUser);

        return redirect()->route('home')->with([
            'success' => true,
            'errors' => [],
            'message' => $message
        ], 200);
    }
}
