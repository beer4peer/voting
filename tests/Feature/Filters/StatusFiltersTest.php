<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\PollsIndex;
use App\Http\Livewire\StatusFilters;
use App\Models\Poll;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class StatusFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_status_filters_livewire_component()
    {
        Poll::factory()->create();

        $this->get(route('poll.index'))
            ->assertSeeLivewire('status-filters');
    }

    /** @test */
    public function show_page_contains_status_filters_livewire_component()
    {
        $poll = Poll::factory()->create();

        $this->get(route('poll.show', $poll))
            ->assertSeeLivewire('status-filters');
    }

    /** @test */
    public function shows_correct_status_count()
    {
        $statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

        Poll::factory()->create([
            'status_id' => $statusImplemented->id,
        ]);

        Poll::factory()->create([
            'status_id' => $statusImplemented->id,
        ]);

        Livewire::test(StatusFilters::class)
            ->assertSee('All Polls (2)')
            ->assertSee('Implemented (2)');
    }

    /** @test */
    public function filtering_works_when_query_string_in_place()
    {
        $statusOpen = Status::factory()->create(['name' => 'Open']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['name' => 'In Progress']);
        $statusImplemented = Status::factory()->create(['name' => 'Implemented']);
        $statusClosed = Status::factory()->create(['name' => 'Closed']);

        Poll::factory()->create([
            'status_id' => $statusConsidering->id,
        ]);

        Poll::factory()->create([
            'status_id' => $statusConsidering->id,
        ]);

        Poll::factory()->create([
            'status_id' => $statusInProgress->id,
        ]);

        Poll::factory()->create([
            'status_id' => $statusInProgress->id,
        ]);

        Poll::factory()->create([
            'status_id' => $statusInProgress->id,
        ]);

        Livewire::withQueryParams(['status' => 'In Progress'])
            ->test(PollsIndex::class)
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 3
                    && $polls->first()->status->name === 'In Progress';
            });
    }

    /** @test */
    public function show_page_does_not_show_selected_status()
    {
        $statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

        $poll = Poll::factory()->create([
            'status_id' => $statusImplemented->id,
        ]);

        $response = $this->get(route('poll.show', $poll));

        $response->assertDontSee('border-blue text-gray-900');
    }

    /** @test */
    public function index_page_shows_selected_status()
    {
        $statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

        $poll = Poll::factory()->create([
            'status_id' => $statusImplemented->id,
        ]);

        $response = $this->get(route('poll.index'));

        $response->assertSee('border-blue text-gray-900');
    }
}
