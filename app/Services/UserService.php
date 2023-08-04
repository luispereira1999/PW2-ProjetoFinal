<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Esta classe trata das várias operações relativas aos utilizadores.
 */
class UserService
{
    /**
     * Obter um utilizador pelo identificador do utilizador.
     *
     * @param  int $userId  Identificador do utilizador.
     * @return \App\Models\User  O utilizador obtido.
     */
    public function getOne($userId)
    {
        $user = User::findOrFail($userId);
        return $user;
    }


    /**
     * Inserir um novo utilizador na base de dados.
     *
     * @param  string $name  Nome do utilizador.
     * @param  string $password  Palavra-passe (não encriptada).
     * @param  string $email  Endereço de email.
     * @return \App\Models\User  O utilizador inserido.
     */
    public function insertOne($name, $password, $email)
    {
        $user = new User();
        $user->name = $name;
        $user->password = Hash::make($password);  // encripta a palavra-passe
        $user->email = $email;
        $user->save();

        return $user;
    }


    /**
     * Atualizar os dados de um utilizador.
     *
     * @param  \App\Models\User $user  Objeto do modelo do utilizador que será atualizado.
     * @param  string $name  Nome do utilizador.
     * @param  string $email  Endereço de email.
     * @param  string $first_name  Primeiro nome.
     * @param  string $last_name  Último nome.
     * @param  string $city  País.
     * @param  string $country  Cidade.
     * @return string  A mensagem de sucesso.
     */
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


    /**
     * Atualizar a palavra-passe de um utilizador.
     *
     * @param  \App\Models\User $user  Objeto do modelo do utilizador que será atualizado.
     * @param  string $password  Palavra-passe (não encriptada).
     * @return string  A mensagem de sucesso.
     */
    public function updatePasswordOne($user, $password)
    {
        $user->password = Hash::make($password);  // encripta a palavra-passe
        $user->save();

        return 'Utilizador atualizado com sucesso.';
    }


    /**
     * Remover um utilizador.
     *
     * @param  \App\Models\User $user  Objeto do modelo do utilizador que será removido.
     * @return string  A mensagem de sucesso.
     */
    public function deleteOne($user)
    {
        $user->delete();
        return 'Utilizador removido com sucesso.';
    }
}
