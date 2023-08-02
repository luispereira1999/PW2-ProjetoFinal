<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
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


    public function getOne($postId)
    {
        // $post = Post::find($postId);
        // return $post;
    }


    public function updateOne($post, $title, $description)
    {
        // $post->title = $title;
        // $post->description = $description;
        // $post->save();

        // return ['success' => true, 'message' => 'Post atualizado com sucesso.'];
    }


    public function delete($user)
    {
        $user->delete();

        return ['success' => true, 'message' => 'Utilizador apagado com sucesso.'];
    }
}
