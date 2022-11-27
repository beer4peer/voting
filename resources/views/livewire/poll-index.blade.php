<div
    x-data
    @click="
        const clicked = $event.target
        const target = clicked.tagName.toLowerCase()
        const ignores = ['button', 'svg', 'path', 'a']
        if (! ignores.includes(target)) {
            clicked.closest('.poll-container').querySelector('.poll-link').click()
        }
    "
    class="poll-container hover:shadow-card transition duration-150 ease-in bg-white rounded-xl flex cursor-pointer"
>
    <div class="hidden md:block border-r border-gray-100 px-5 py-8">
        <div class="w-50 flex flex-row">
            <x-poll-vote :poll="$poll" :hasVoted="$hasVoted"/>
        </div>
    </div>
    <div class="flex flex-col md:flex-row flex-1 px-2 py-6">
        <div class="flex-none mx-2 md:mx-0">
            <a href="#">
                <img src="{{ asset("img/logo.jpg") }}" alt="avatar" class="w-14 h-14 rounded-xl">
            </a>
        </div>
        <div class="w-full flex flex-col justify-between mx-2 md:mx-4">
            <h4 class="text-xl font-semibold mt-2 md:mt-0">
                <a href="{{ route('poll.show', $poll) }}" class="poll-link hover:underline">{{ $poll->title }}</a>
            </h4>
            <div class="text-gray-600 mt-3 line-clamp-3">
                <x-markdown>
                    {!! $poll->description !!}
                </x-markdown>

            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                    <div>Created {{ $poll->created_at?->diffForHumans() ?? '-' }}</div>
                    <div>&bull;</div>
                    <div>{{ $poll->ope?->isPast() ? 'Ended' : 'Ends'}} {{ $poll->ends_at?->diffForHumans() ?? '-' }}</div>
                    <div>&bull;</div>
                    <div>{{ $poll->ends_at?->isPast() ? 'Ended' : 'Ends'}} {{ $poll->ends_at?->diffForHumans() ?? '-' }}</div>
                    <div>&bull;</div>
                    <div>{{ $poll->category->name }}</div>
                    <div>&bull;</div>
                    <div wire:ignore class="text-gray-900">
                        {{ $poll->comments->where('is_voting', false)->count() }} {{ Str::plural('comment', $poll->comments->where('is_voting', false)->count()) }}
                    </div>
                </div>
                <div
                    x-data="{ isOpen: false }"
                    class="flex items-center space-x-2 mt-4 md:mt-0"
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

                <div class="flex items-center md:hidden mt-4 md:mt-0">
                    <x-poll-vote :poll="$poll" :hasVoted="$hasVoted"/>
                </div>
            </div>
        </div>
    </div>
</div>
