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

class CategoryFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function selecting_a_category_filters_correctly()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->set('category', 'Category 1')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2
                    && $polls->first()->category->name === 'Category 1';
            });
    }

    /** @test */
    public function the_category_query_string_filters_correctly()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
        ]);

        Livewire::withQueryParams(['category' => 'Category 1'])
            ->test(PollsIndex::class)
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 2
                    && $polls->first()->category->name === 'Category 1';
            });
    }

    /** @test */
    public function selecting_a_status_and_a_category_filters_correctly()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
        ]);

        $pollFour = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
            'status_id' => $statusConsidering->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->set('status', 'Open')
            ->set('category', 'Category 1')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 1
                    && $polls->first()->category->name === 'Category 1'
                    && $polls->first()->status->name === 'Open';
            });
    }

    /** @test */
    public function the_category_query_string_filters_correctly_with_status_and_category()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
        ]);

        $pollFour = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
            'status_id' => $statusConsidering->id,
        ]);

        Livewire::withQueryParams(['status' => 'Open', 'category' => 'Category 1'])
            ->test(PollsIndex::class)
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 1
                    && $polls->first()->category->name === 'Category 1'
                    && $polls->first()->status->name === 'Open';
            });
    }

    /** @test */
    public function selecting_all_categories_filters_correctly()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $pollOne = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollTwo = Poll::factory()->create([
            'category_id' => $categoryOne->id,
        ]);

        $pollThree = Poll::factory()->create([
            'category_id' => $categoryTwo->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->set('category', 'All Categories')
            ->assertViewHas('polls', function ($polls) {
                return $polls->count() === 3;
            });
    }
}
