<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;
use App\Services\UserService;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    protected $authService;
    protected $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }


    /**
     * Ir para a página do perfil do utilizador.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        $userProfile = User::one($userId);
        $loggedUserId = $this->authService->getUserId();

        $posts = Post::allInProfile($userId, $loggedUserId);

        return response()->view('profile', [
            'user' => $userProfile,
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ], 200);
    }


    /**
     * Ir para a página de definições da conta.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
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

        $user = User::findOrFail($userId);

        return response()->view('account', [
            'user' => $user
        ], 200);
    }


    /**
     * Atualizar os dados básicos do utilizador com login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $userId)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O email é obrigatório.'
        ]);

        $loggedUserId = Auth::user()->id;

        if ($loggedUserId != $userId) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $user = User::findOrFail($userId);
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->city = $request->input('city');
        $user->country = $request->input('country');
        $user->save();

        Auth::login($user);

        return back()->with([
            'success' => true,
            'errors' => ['Perfil atualizado com sucesso!']
        ], 200);
    }


    /**
     * Atualizar a palavra-passe do utilizador com login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $userId)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'new_password_confirm' => 'same:new_password'
        ], [
            'current_password.required' => 'A palavra-passe atual é obrigatória.',
            'new_password.required' => 'A nova palavra-passe é obrigatória.',
            'new_password_confirm.same' => 'As palavra-passes não coincidem.',
        ]);

        $loggedUserId = Auth::user()->id;

        if ($loggedUserId != $userId) {
            return redirect()->route('500')->with([
                'success' => false,
                'errors' => ['É necessário fazer login para realizar a operação.']
            ], 500);
        }

        $user = User::findOrFail($userId);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->with([
                'success' => false,
                'errors' => ['A palavra-passe atual fornecida está incorreta.']
            ], 422);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        Auth::login($user);

        return back()->with([
            'success' => true,
            'errors' => ['Perfil atualizado com sucesso!']
        ], 200);
    }


    /**
     * Remover o utilizador com login.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
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

        $result = $this->userService->delete($loggedUser);

        return redirect()->route('home')->with([
            'success' => true,
            'errors' => [],
            'message' => $result['message']
        ], 200);
    }
}
