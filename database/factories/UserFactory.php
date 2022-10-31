<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'nickname' => $this->faker->unique()->userName,
            'slack_id' => $this->faker->unique()->userName,
            'avatar' => $this->faker->imageUrl(),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'slack_id' => 'U0203LX28H0', // Nick Pratley - Dev
            ];
        });
    }
}
