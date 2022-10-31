<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\PollsIndex;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function searching_works_when_more_than_3_characters()
    {
        $pollOne = Poll::factory()->create([
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'title' => 'My Third Poll',
        ]);

        Livewire::test(PollsIndex::class)
            ->set('search', 'Second')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 1
                    && $polls->first()->title === 'My Second Poll';
            });
    }

    /** @test */
    public function does_not_perform_search_if_less_than_3_characters()
    {
        $pollOne = Poll::factory()->create([
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'title' => 'My Third Poll',
        ]);

        Livewire::test(PollsIndex::class)
            ->set('search', 'ab')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 3;
            });
    }

    /** @test */
    public function search_works_correctly_with_category_filters()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'My First Poll',
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'My Second Poll',
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
            'title' => 'My Third Poll',
        ]);

        Livewire::test(PollsIndex::class)
            ->set('category', 'Category 1')
            ->set('search', 'Poll')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2;
            });
    }
}
