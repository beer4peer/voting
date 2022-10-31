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
                    {!! nl2br(e($poll->description)) !!}
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
                        @auth
                            <div class="relative">
                                <button
                                    class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3"
                                    @click="isOpen = !isOpen"
                                >
                                    <svg fill="currentColor" width="24" height="6">
                                        <path
                                            d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z"
                                            style="color: rgba(163, 163, 163, .5)">
                                    </svg>
                                </button>
                                <ul
                                    class="absolute w-44 text-left font-semibold bg-white shadow-dialog rounded-xl z-10 py-3 md:ml-8 top-8 md:top-6 right-0 md:left-0"
                                    x-cloak
                                    x-show.transition.origin.top.left="isOpen"
                                    @click.away="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                >
                                    @can('update', $poll)
                                        <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                isOpen = false
                                                $dispatch('custom-show-edit-modal')
                                            "
                                                class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3"
                                            >
                                                Edit Poll
                                            </a>
                                        </li>
                                    @endcan

                                    @can('delete', $poll)
                                        <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                isOpen = false
                                                $dispatch('custom-show-delete-modal')
                                            "
                                                class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3"
                                            >
                                                Delete Poll
                                            </a>
                                        </li>
                                    @endcan

                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="
                                                isOpen = false
                                                $dispatch('custom-show-mark-poll-as-spam-modal')
                                            "
                                            class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3"
                                        >
                                            Mark as Spam
                                        </a>
                                    </li>

                                    @admin
                                    @if ($poll->spam_reports > 0)
                                        <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    isOpen = false
                                                    $dispatch('custom-show-mark-poll-as-not-spam-modal')
                                                "
                                                class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3"
                                            >
                                                Not Spam
                                            </a>
                                        </li>
                                    @endif
                                    @endadmin
                                </ul>
                            </div>
                        @endauth
                    </div>

                    <div class="flex items-center md:hidden mt-4 md:mt-0 w-10">
                        <div class="bg-white font-semibold text-center rounded-xl px-3 py-2">
                            <div class="flex flex-row">
                                <div class="text-center">
                                    <div class="font-semibold text-2xl text-red-500">{{ $poll->votes_no }}</div>
                                    <div class="text-red-500">No</div>
                                </div>

                                <div class="text-center ml-4">
                                    <div class="font-semibold text-2xl text-green-500 ">{{ $poll->votes_yes }}</div>
                                    <div class="text-green-500">Yes</div>
                                </div>
                            </div>
                        </div>


                        @auth
                            @if (!$hasVoted && $poll->openForVoting())
                                <button wire:click.prevent="voteNo"
                                        class="w-20 bg-red-200 border border-red-200 hover:border-red-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">
                                    Vote No
                                </button>
                                <button wire:click.prevent="voteYes"
                                        class="w-20 bg-green-200 border border-green-200 hover:border-green-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3 mr-3">
                                    Vote Yes
                                </button>
                            @endif
                        @endauth
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
            @auth
                @if (!$hasVoted && $poll->openForVoting())
                    <button wire:click.prevent="voteNo"
                            class="w-20 bg-red-200 border border-red-200 hover:border-red-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">
                        Vote No
                    </button>
                    <button wire:click.prevent="voteYes"
                            class="w-20 bg-green-200 border border-green-200 hover:border-green-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3 mr-3">
                        Vote Yes
                    </button>
                @endif
            @endauth

            <div class="bg-white font-semibold text-center rounded-xl px-3 py-2">
                <div class="flex flex-row">
                    <div class="text-center">
                        <div class="font-semibold text-2xl text-red-500">{{ $poll->votes_no }}</div>
                        <div class="text-red-500">No</div>
                    </div>

                    <div class="text-center ml-4">
                        <div class="font-semibold text-2xl text-green-500 ">{{ $poll->votes_yes }}</div>
                        <div class="text-green-500">Yes</div>
                    </div>
                </div>
            </div>

        </div>
    </div> <!-- end buttons-container -->
</div>
