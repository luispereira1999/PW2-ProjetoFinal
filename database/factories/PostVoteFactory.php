<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VoteType;
use App\Models\PostVote;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostVoteFactory extends Factory
{
    protected $model = PostVote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // outros atributos se necessário
            'vote_type_id' => VoteType::inRandomOrder()->first()->id
        ];
    }

    public function createWithPostAndUser($postId, $userId)
    {
        return $this->state([
            'post_id' => $postId,
            'user_id' => $userId
        ])->create();
    }
}
