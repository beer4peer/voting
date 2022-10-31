<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\AddComment;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use App\Notifications\CommentAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_comment_livewire_component_renders()
    {
        $poll = Poll::factory()->create();

        $response = $this->get(route('poll.show', $poll));

        $response->assertSeeLivewire('add-comment');
    }

    /** @test */
    public function add_comment_form_renders_when_user_is_logged_in()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $response = $this->actingAs($user)->get(route('poll.show', $poll));

        $response->assertSee('Share your thoughts');
    }

    /** @test */
    public function add_comment_form_does_not_render_when_user_is_logged_out()
    {
        $poll = Poll::factory()->create();

        $response = $this->get(route('poll.show', $poll));

        $response->assertSee('Please login or create an account to post a comment');
    }

    /** @test */
    public function add_comment_form_validation_works()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(AddComment::class, [
                'poll' => $poll,
            ])
            ->set('comment', '')
            ->call('addComment')
            ->assertHasErrors(['comment'])
            ->set('comment', 'ab')
            ->call('addComment')
            ->assertHasErrors(['comment']);
    }

    /** @test */
    public function add_comment_form__works()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Notification::fake();

        Notification::assertNothingSent();

        Livewire::actingAs($user)
            ->test(AddComment::class, [
                'poll' => $poll,
            ])
            ->set('comment', 'This is my first comment')
            ->call('addComment')
            ->assertEmitted('commentWasAdded');

        Notification::assertSentTo(
            [$poll->user],
            CommentAdded::class
        );

        $this->assertEquals(1, Comment::count());
        $this->assertEquals('This is my first comment', $poll->comments->first()->body);
    }
}
