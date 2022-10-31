<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create(['name' => 'Slack']);

        Status::factory()->create(['name' => 'Open']);
        Status::factory()->create(['name' => 'Accepted']);
        Status::factory()->create(['name' => 'Rejected']);

    }
}
