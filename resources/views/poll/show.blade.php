<x-app-layout>
    <x-slot name="title">
        {{ $poll->title }} | B4P - Voting
    </x-slot>
    <div>
        <a href="{{ $backUrl }}" class="flex items-center font-semibold hover:underline">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="ml-2">Back</span>
        </a>
    </div>

    <livewire:poll-show
        :poll="$poll"
        :votesCount="$votesCount"
    />

    <livewire:poll-comments :poll="$poll" />

    <x-modals-container :poll="$poll" />
</x-app-layout>
