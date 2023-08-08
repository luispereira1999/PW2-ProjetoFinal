<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VoteType;
use App\Models\CommentVote;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class CommentVoteFactory extends Factory
{
    protected $model = CommentVote::class;

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

    public function createWithCommentAndUser($commentId, $userId)
    {
        return $this->state([
            'comment_id' => $commentId,
            'user_id' => $userId
        ])->create();
    }
}
