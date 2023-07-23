<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userProfileId)
    {
        $userProfile = User::one($userProfileId);

        $userLogged = Auth::user();
        $loggedUserId = $userLogged ? $userLogged->id : -1;

        $posts = Post::allInProfile($userProfileId, $loggedUserId);

        return view('profile', [
            'user' => $userProfile,
            'posts' => $posts,
            'userLoggedId' => $loggedUserId
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the user logged.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show()
    {
        $user = Auth::user();
        $loggedUserId = $user ? $user->id : -1;

        $user = User::one($loggedUserId);

        return view('account', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
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

        if (!Auth::check()) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $loggedUserId = Auth::user()->id;
        if ($loggedUserId != $userId) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $user = User::findOrFail($userId);

        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->city = $request->input('city');
        $user->country = $request->input('country');

        $user->save();

        Auth::login($user);

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!')
            ->with('reload', true);
    }


    /**
     * Update the specified resource in storage.
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
            'new_password_confirm.same' => 'As palavra-passes não correspondem.',
        ]);

        if (!Auth::check()) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $loggedUserId = Auth::user()->id;
        if ($loggedUserId != $userId) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $user = User::findOrFail($userId);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', 'A palavra-passe atual fornecida está incorreta.');
        }

        $user->password = Hash::make($request->input('new_password'));

        $user->save();

        Auth::login($user);

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!')
            ->with('reload', true);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
