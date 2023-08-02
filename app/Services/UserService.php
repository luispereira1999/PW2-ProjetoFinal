<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{
    public function getAll($loggedUserId)
    {
        // $posts = DB::table('posts as p')
        //     ->select(
        //         'p.id as post_id',
        //         'p.title',
        //         'p.description',
        //         'p.date',
        //         'p.votes_amount',
        //         'p.comments_amount',
        //         'p.user_id as post_user_id',
        //         'u.name as post_user_name',
        //         'v.user_id as vote_user_id',
        //         'v.vote_type_id'
        //     )
        //     ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
        //     ->leftJoin('posts_votes as v', function ($join) use ($loggedUserId) {
        //         $join->on('p.id', '=', 'v.post_id')
        //             ->where('v.user_id', '=', $loggedUserId);
        //     })
        //     ->orderBy('p.date', 'desc')
        //     ->get();

        // return $posts;
    }


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

        return ['message' => 'Utilizador atualizado com sucesso.'];
    }


    public function updatePasswordOne($user, $password)
    {
        $user->password = Hash::make($password);  // encripta a palavra-passe
        $user->save();

        return ['message' => 'Utilizador atualizado com sucesso.'];
    }


    public function deleteOne($user)
    {
        $user->delete();
        return ['message' => 'Utilizador removido com sucesso.'];
    }
}
