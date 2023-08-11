<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        $title = mb_substr($title, 0, 50);  // limita até 50 caracteres

        return [
            'title' => $title,
            'description' => implode("\n", $this->faker->paragraphs(10)),
            'date' => $this->faker->dateTimeThisYear,
            'votes_amount' => 0,
            'comments_amount' => 0,
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
