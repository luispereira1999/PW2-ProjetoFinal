<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{
    public function getOne($userId)
    {
        $user = User::findOrFail($userId);
        return $user;
    }


    public function insertOne($name, $password, $email)
    {
        $user = new User();
        $user->name = $name;
        $user->password = Hash::make($password);  // encripta a palavra-passe
        $user->email = $email;
        $user->save();

        return ['user' => $user, 'message' => 'Registo criado com sucesso.'];
    }


    public function updateDataOne($user, $name, $email, $first_name, $last_name, $city, $country)
    {
        $user->name = $name;
        $user->email = $email;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->city = $city;
        $user->country = $country;
        $user->save();

        return 'Utilizador atualizado com sucesso.';
    }


    public function updatePasswordOne($user, $password)
    {
        $user->password = Hash::make($password);  // encripta a palavra-passe
        $user->save();

        return 'Utilizador atualizado com sucesso.';
    }


    public function deleteOne($user)
    {
        $user->delete();
        return 'Utilizador removido com sucesso.';
    }
}
