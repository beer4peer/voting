<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\EditComment;
use App\Http\Livewire\EditPoll;
use App\Http\Livewire\PollComment;
use App\Http\Livewire\PollShow;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class EditCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_edit_comment_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('edit-comment');
    }

    /** @test */
    public function does_not_show_edit_comment_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('edit-comment');
    }

    /** @test */
    public function edit_comment_is_set_correctly_when_user_clicks_it_from_menu()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->assertSet('body', $comment->body)
            ->assertEmitted('editCommentWasSet');
    }

    /** @test */
    public function edit_comment_form_validation_works()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', '')
            ->call('updateComment')
            ->assertHasErrors(['body'])
            ->set('body', 'ab')
            ->call('updateComment')
            ->assertHasErrors(['body']);
    }

    /** @test */
    public function editing_a_comment_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is my updated comment')
            ->call('updateComment')
            ->assertEmitted('commentWasUpdated');

        $this->assertEquals('This is my updated comment', Comment::first()->body);
    }

    /** @test */
    public function editing_a_comment_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is my updated comment')
            ->call('updateComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function editing_a_comment_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id,
            ])
            ->assertSee('Edit Comment');
    }

    /** @test */
    public function editing_a_comment_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id,
            ])
            ->assertDontSee('Edit Comment');
    }
}
