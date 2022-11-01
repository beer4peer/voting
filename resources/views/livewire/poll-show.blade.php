<div class="poll-and-buttons">

    <div class="poll-container bg-white rounded-xl flex mt-4">
        <div class="flex flex-col md:flex-row flex-1 px-4 py-6">
            <div class="flex-none mx-2">
                <a href="#">
                    <img src="{{ asset("img/logo.jpg") }}" alt="avatar" class="w-14 h-14 rounded-xl">
                </a>
            </div>
            <div class="w-full mx-2 md:mx-4">
                <h4 class="text-xl font-semibold mt-2 md:mt-0">
                    {{ $poll->title }}
                </h4>
                <div class="text-gray-600 mt-3">
                    <x-markdown>{!! nl2br($poll->description) !!}</x-markdown>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                        <div>Created {{ $poll->created_at?->diffForHumans() ?? '-' }}</div>
                        <div>&bull;</div>
                        <div>Ends {{ $poll->ends_at?->diffForHumans() ?? '-' }}</div>
                        <div>&bull;</div>
                        <div>{{ $poll->category->name }}</div>
                        <div>&bull;</div>
                        <div class="text-gray-900">
                            {{ $poll->comments->where('is_voting', false)->count() }} {{ Str::plural('comment', $poll->comments->where('is_voting', false)->count()) }}
                        </div>
                    </div>
                    <div
                        class="flex items-center space-x-2 mt-4 md:mt-0"
                        x-data="{ isOpen: false }"
                    >
                        <div
                            class="{{ 'status-'.Str::kebab($poll->status->name) }} @if($poll->ends_at?->isPast()) status-closed @endif text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">
                            @if($poll->ends_at?->isPast())
                                Closed
                            @else
                                {{ $poll->status->name }}
                            @endif
                        </div>

                    </div>

                    <div class="flex items-center md:hidden mt-4 md:mt-0 w-10">
                        <x-poll-vote :poll="$poll" :hasVoted="$hasVoted"/>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end poll-container -->

    <div class="buttons-container flex items-center justify-between mt-6">
        <div class="flex flex-col md:flex-row items-center space-x-4 md:ml-6">
            <livewire:add-comment :poll="$poll"/>
            @admin
            <livewire:set-status :poll="$poll"/>
            @endadmin

        </div>

        <div class="hidden md:flex items-center space-x-3">
            <x-poll-vote :poll="$poll" :hasVoted="$hasVoted"/>
        </div>
    </div> <!-- end buttons-container -->
</div>
