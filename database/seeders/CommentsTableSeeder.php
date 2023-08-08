<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory(20)->create();

        // obtém os comentários que pertencem aos posts
        $postsWithComments = Comment::select('post_id')
            ->groupBy('post_id')
            ->get();

        // para cada post, atualiza a quantidade de comentários desse post
        foreach ($postsWithComments as $post) {
            $commentsAmount = Comment::where('post_id', $post->post_id)
                ->count();

            Post::Where('id', $post->post_id)
                ->update(['comments_amount' => $commentsAmount]);
        }
    }
}
