@props([
    'poll' => new \App\Models\Poll(),
])

@if($poll->ends_at?->isFuture())

    <div class="flex flex-col space-x-1 space-y-2">
        <div class="text-center w-20">
            <div class="font-semibold text-2xl text-blue">{{ $poll->votes->count() }}</div>
            <div class="text-blue">{{ Str::plural('Vote', $poll->votes->count()) }}</div>
        </div>


        @if ($poll->openForVoting() && !$poll->isVotedByUser(auth()->user()))

            <button wire:click.prevent="voteYes"
                    class="pt-2 w-20 bg-green-200 border border-green-200 hover:border-green-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3 mr-3">
                Vote Yes
            </button>
            <button wire:click.prevent="voteNo"
                    class="pt-2 w-20 bg-red-200 border border-red-200 hover:border-red-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">
                Vote No
            </button>
        @endif

    </div>

@else
    <div class="flex flex-col espace-y-2 justify-between items-center">
        <div class="text-center w-20">
            <div class="font-semibold text-2xl text-green-500">{{ $poll->votes_yes }}</div>
            <div class="text-green-500">Yes</div>
        </div>
        <div class="text-center w-20">
            <div class="font-semibold text-2xl text-red-500">{{ $poll->votes_no }}</div>
            <div class="text-red-500">No</div>
        </div>
    </div>
@endif



