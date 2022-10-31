<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\PollsIndex;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_voted_filter_works()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $pollOne = Poll::factory()->create();
        $pollTwo = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $pollOne->id,
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'poll_id' => $pollOne->id,
            'user_id' => $userB->id,
        ]);

        Vote::factory()->create([
            'poll_id' => $pollTwo->id,
            'user_id' => $userC->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->set('filter', 'Top Voted')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2
                    && $polls->first()->votes()->count() === 2
                    && $polls->get(1)->votes()->count() === 1;
            });
    }

    /** @test */
    public function my_polls_filter_works_correctly_when_user_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $pollOne = Poll::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'user_id' => $userB->id,
            'title' => 'My Third Poll',
        ]);

        Livewire::actingAs($user)
            ->test(PollsIndex::class)
            ->set('filter', 'My Polls')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2
                    && $polls->first()->title === 'My Second Poll'
                    && $polls->get(1)->title === 'My First Poll';
            });
    }

    /** @test */
    public function my_polls_filter_works_correctly_when_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $pollOne = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        $pollThree = Poll::factory()->create([
            'user_id' => $userB->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->set('filter', 'My Polls')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function my_polls_filter_works_correctly_with_categories_filter()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $pollOne = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryTwo->id,
            'title' => 'My Third Poll',
        ]);

        Livewire::actingAs($user)
            ->test(PollsIndex::class)
            ->set('category', 'Category 1')
            ->set('filter', 'My Polls')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2
                    && $polls->first()->title === 'My Second Poll'
                    && $polls->get(1)->title === 'My First Poll';
            });
    }

    /** @test */
    public function no_filters_works_correctly()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'My Third Poll',
        ]);

        Livewire::test(PollsIndex::class)
            ->set('filter', 'No Filter')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 3
                    && $polls->first()->title === 'My Third Poll'
                    && $polls->get(1)->title === 'My Second Poll';
            });
    }

    /** @test */
    public function spam_polls_filter_works()
    {
        $user = User::factory()->admin()->create();

        $pollOne = Poll::factory()->create([
            'title' => 'Poll One',
            'spam_reports' => 1
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'Poll Two',
            'spam_reports' => 2
        ]);

        $pollThree = Poll::factory()->create([
            'title' => 'Poll Three',
            'spam_reports' => 3
        ]);

        $pollFour = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(PollsIndex::class)
            ->set('filter', 'Spam Polls')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 3
                    && $polls->first()->title === 'Poll Three'
                    && $polls->get(1)->title === 'Poll Two'
                    && $polls->get(2)->title === 'Poll One';
            });
    }

    /** @test */
    public function spam_comments_filter_works()
    {
        $user = User::factory()->admin()->create();

        $pollOne = Poll::factory()->create([
            'title' => 'Poll One',
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'Poll Two',
        ]);

        $pollThree = Poll::factory()->create([
            'title' => 'Poll Two',
        ]);

        $commentOne = Comment::factory()->create([
            'poll_id' => $pollOne->id,
            'body' => 'This is my first comment',
            'spam_reports' => 3,
        ]);

        $commentTwo = Comment::factory()->create([
            'poll_id' => $pollTwo->id,
            'body' => 'This is my second comment',
            'spam_reports' => 2,
        ]);

        Livewire::actingAs($user)
            ->test(PollsIndex::class)
            ->set('filter', 'Spam Comments')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2;
            });
    }
}
