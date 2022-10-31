<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function poll_comments_livewire_component_renders()
    {
        $poll = Poll::factory()->create();

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSeeLivewire('poll-comments');
    }

    /** @test */
    public function poll_comment_livewire_component_renders()
    {
        $poll = Poll::factory()->create();

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSeeLivewire('poll-comment');
    }

    /** @test */
    public function no_comments_shows_appropriate_message()
    {
        $poll = Poll::factory()->create();

        $response = $this->get(route('poll.show', $poll));

        $response->assertSee('No comments yet');
    }

    /** @test */
    public function list_of_comments_shows_on_poll_page()
    {
        $poll = Poll::factory()->create();

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $commentTwo = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my second comment',
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSeeInOrder(['This is my first comment', 'This is my second comment']);
        $response->assertSee('2 comments');
    }

    /** @test */
    public function comments_count_shows_correctly_on_index_page()
    {
        $poll = Poll::factory()->create();

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $commentTwo = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my second comment',
        ]);

        $response = $this->get(route('poll.index'));

        $response->assertSee('2 comments');
    }

    /** @test */
    public function op_badge_shows_if_author_of_poll_comments_on_poll()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $commentTwo = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my second comment',
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSee('OP');
    }

    /** @test */
    public function comments_pagination_works()
    {
        $poll = Poll::factory()->create();

        $commentOne = Comment::factory()->create([
            'poll_id' => $poll
        ]);

        Comment::factory($commentOne->getPerPage())->create([
            'poll_id' => $poll->id,
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertSee($commentOne->body);
        $response->assertDontSee(Comment::find(Comment::count())->body);

        $response = $this->get(route('poll.show', [
            'poll' => $poll,
            'page' => 2,
        ]));

        $response->assertDontSee($commentOne->body);
        $response->assertSee(Comment::find(Comment::count())->body);
    }
}
