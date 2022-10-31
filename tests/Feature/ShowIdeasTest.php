<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Poll;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowPollsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_polls_shows_on_main_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'OpenUnique']);
        $statusConsidering = Status::factory()->create(['name' => 'ConsideringUnique']);

        $pollOne = Poll::factory()->create([
            'title' => 'My First Poll',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'My Second Poll',
            'category_id' => $categoryTwo->id,
            'status_id' => $statusConsidering->id,
        ]);

        $response = $this->get(route('poll.index'));

        $response->assertSuccessful();
        $response->assertSee($pollOne->title);
        $response->assertSee($pollOne->description);
        $response->assertSee($categoryOne->name);
        $response->assertSee('OpenUnique');
        $response->assertSee($pollTwo->title);
        $response->assertSee($pollTwo->description);
        $response->assertSee($categoryTwo->name);
        $response->assertSee('ConsideringUnique');
    }

    /** @test */
    public function single_poll_shows_correctly_on_the_show_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'OpenUnique']);

        $poll = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Poll',
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSuccessful();
        $response->assertSee($poll->title);
        $response->assertSee($poll->description);
        $response->assertSee($categoryOne->name);
        $response->assertSee('OpenUnique');
    }

    /** @test */
    public function polls_pagination_works()
    {
        $pollOne = Poll::factory()->create();

        Poll::factory($pollOne->getPerPage())->create();

        $response = $this->get('/');

        $response->assertSee(Poll::find(Poll::count())->title);
        $response->assertDontSee($pollOne->title);

        $response = $this->get('/?page=2');

        $response->assertDontSee(Poll::find(Poll::count())->title);
        $response->assertSee($pollOne->title);
    }

    /** @test */
    public function same_poll_title_different_slugs()
    {
        $pollOne = Poll::factory()->create([
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'My First Poll',
        ]);

        $response = $this->get(route('poll.show', $pollOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'polls/my-first-poll');

        $response = $this->get(route('poll.show', $pollTwo));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'polls/my-first-poll-2');
    }

    /** @test */
    public function in_app_back_button_works_when_index_page_visited_first()
    {
        $pollOne = Poll::factory()->create();

        $response = $this->get('/?category=Category%202&status=Considering');
        $response = $this->get(route('poll.show', $pollOne));

        $this->assertStringContainsString('/?category=Category%202&status=Considering', $response['backUrl']);
    }

    /** @test */
    public function in_app_back_button_works_when_show_page_only_page_visited()
    {
        $pollOne = Poll::factory()->create();

        $response = $this->get(route('poll.show', $pollOne));

        $this->assertEquals(route('poll.index'), $response['backUrl']);
    }
}
