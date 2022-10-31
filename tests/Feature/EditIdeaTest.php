<?php

namespace Tests\Feature;

use App\Http\Livewire\EditPoll;
use App\Http\Livewire\PollShow;
use App\Models\Category;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class EditPollTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_edit_poll_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('edit-poll');
    }

    /** @test */
    public function does_not_show_edit_poll_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('edit-poll');
    }

    /** @test */
    public function edit_poll_form_validation_works()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(EditPoll::class, [
                'poll' => $poll,
            ])
            ->set('title', '')
            ->set('category', '')
            ->set('description', '')
            ->call('updatePoll')
            ->assertHasErrors(['title', 'category', 'description'])
            ->assertSee('The title field is required');
    }

    /** @test */
    public function editing_an_poll_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne,
        ]);

        Livewire::actingAs($user)
            ->test(EditPoll::class, [
                'poll' => $poll,
            ])
            ->set('title', 'My Edited Poll')
            ->set('category', $categoryTwo->id)
            ->set('description', 'This is my edited poll')
            ->call('updatePoll')
            ->assertEmitted('pollWasUpdated');

        $this->assertDatabaseHas('polls', [
            'title' => 'My Edited Poll',
            'description' => 'This is my edited poll',
            'category_id' => $categoryTwo->id,
        ]);
    }

    /** @test */
    public function editing_an_poll_does_not_work_when_user_does_not_have_authorization_because_different_user_created_poll()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne,
        ]);

        Livewire::actingAs($userB)
            ->test(EditPoll::class, [
                'poll' => $poll,
            ])
            ->set('title', 'My Edited Poll')
            ->set('category', $categoryTwo->id)
            ->set('description', 'This is my edited poll')
            ->call('updatePoll')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function editing_an_poll_does_not_work_when_user_does_not_have_authorization_because_poll_was_created_longer_than_an_hour_ago()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne,
            'created_at' => now()->subHours(2),
        ]);

        Livewire::actingAs($user)
            ->test(EditPoll::class, [
                'poll' => $poll,
            ])
            ->set('title', 'My Edited Poll')
            ->set('category', $categoryTwo->id)
            ->set('description', 'This is my edited poll')
            ->call('updatePoll')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function editing_an_poll_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Edit Poll');
    }

    /** @test */
    public function editing_an_poll_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertDontSee('Edit Poll');
    }
}
