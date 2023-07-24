<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display a page of the authentication.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth');
    }


    /**
     * Handle an registration of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ], [
            'name.required' => 'O nome de utilizador é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.confirmed' => 'As palavra-passes não correspondem.'
        ]);

        // encriptar palavra-passe
        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);

        auth()->login($user);

        return redirect()->intended('/');
    }


    /**
     * Handle an authentication attempt.
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'name' => 'Credenciais de acesso inválidas.',
        ])->onlyInput('name');
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
