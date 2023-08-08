<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\PostVote;

class PostsVotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $combinations = [];
        $numberOfCombinations = 20;
        $current = 0;

        // obtém as combinações únicas para as chaves primárias de cada registo
        while ($current < $numberOfCombinations) {
            $postId = Post::inRandomOrder()->first()->id;
            $userId = User::inRandomOrder()->first()->id;

            $combination = "{$postId}_{$userId}";

            $exists = PostVote::where('post_id', $postId)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {
                if (!in_array($combination, $combinations)) {
                    $combinations[] = $combination;
                }
            }

            $current++;
        }

        foreach ($combinations as $combination) {
            [$postId, $userId] = explode('_', $combination);

            PostVote::factory()->createWithPostAndUser($postId, $userId);
        }
    }
}
