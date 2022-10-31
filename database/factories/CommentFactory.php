<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Poll;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'poll_id' => Poll::factory(),
            'status_id' => Status::factory(),
            'body' => $this->faker->paragraph(5),
        ];
    }
}
