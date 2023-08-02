<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Ir para a página de autenticação.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth');
    }


    /**
     * Iniciar sessão de um utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ], [
            'name.required' => 'O nome de utilizador é obrigatório.',
            'password.required' => 'A palavra-passe é obrigatória.'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'errors' => ['Credenciais de acesso inválidas.']
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => 'Login efetuado com sucesso.'
        ], 200);
    }


    /**
     * Registar um utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        // encriptar palavra-passe
        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);

        auth()->login($user);

        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => 'Registo efetuado com sucesso.'
        ], 201);
    }


    /**
     * Terminar sessão de um utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with([
            'success' => true,
            'errors' => [],
            'message' => 'Utilizador terminou sessão com sucesso.'
        ], 200);
    }
}
