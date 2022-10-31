<?php

namespace Tests\Feature;

use App\Http\Livewire\CreatePoll;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePollTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_poll_form_does_not_show_when_logged_out()
    {
        $response = $this->get(route('poll.index'));

        $response->assertSuccessful();
        $response->assertSee('Please login to create an poll.');
        $response->assertDontSee('Let us know what you would like and we\'ll take a look over!', false);
    }

    /** @test */
    public function create_poll_form_does_show_when_logged_in()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('poll.index'));

        $response->assertSuccessful();
        $response->assertDontSee('Please login to create an poll.');
        $response->assertSee('Let us know what you would like and we\'ll take a look over!', false);
    }

    /** @test */
    public function main_page_contains_create_poll_livewire_component()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('poll.index'))
            ->assertSeeLivewire('create-poll');
    }

    /** @test */
    public function create_poll_form_validation_works()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreatePoll::class)
            ->set('title', '')
            ->set('category', '')
            ->set('description', '')
            ->call('createPoll')
            ->assertHasErrors(['title', 'category', 'description'])
            ->assertSee('The title field is required');
    }

    /** @test */
    public function creating_an_poll_works_correctly()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        Livewire::actingAs($user)
            ->test(CreatePoll::class)
            ->set('title', 'My First Poll')
            ->set('category', $categoryOne->id)
            ->set('description', 'This is my first poll')
            ->call('createPoll')
            ->assertRedirect('/');

        $response = $this->actingAs($user)->get(route('poll.index'));
        $response->assertSuccessful();
        $response->assertSee('My First Poll');
        $response->assertSee('This is my first poll');

        $this->assertDatabaseHas('polls', [
            'title' => 'My First Poll'
        ]);

        $this->assertDatabaseHas('votes', [
            'poll_id' => 1,
            'user_id' => 1,
        ]);
    }

    /** @test */
    public function creating_two_polls_with_same_title_still_works_but_has_different_slugs()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        Livewire::actingAs($user)
            ->test(CreatePoll::class)
            ->set('title', 'My First Poll')
            ->set('category', $categoryOne->id)
            ->set('description', 'This is my first poll')
            ->call('createPoll')
            ->assertRedirect('/');

        $this->assertDatabaseHas('polls', [
            'title' => 'My First Poll',
            'slug' => 'my-first-poll'
        ]);

        Livewire::actingAs($user)
            ->test(CreatePoll::class)
            ->set('title', 'My First Poll')
            ->set('category', $categoryOne->id)
            ->set('description', 'This is my first poll')
            ->call('createPoll')
            ->assertRedirect('/');

        $this->assertDatabaseHas('polls', [
            'title' => 'My First Poll',
            'slug' => 'my-first-poll-2'
        ]);
    }
}
