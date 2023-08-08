<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail;
        $name = substr($email, 0, strpos($email, '@'));  // extrai o nome antes do @

        return [
            'name' => $name,
            'password' => Hash::make('123456'),
            'email' => $email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'city' => $this->faker->city,
            'country' => $this->faker->country
        ];
    }
}
