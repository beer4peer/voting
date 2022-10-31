<div>
    @if ($comments->isNotEmpty())

        <div class="comments-container relative space-y-6 md:ml-22 pt-4 my-8 mt-1">

            @foreach ($comments as $comment)
                @if($comment->is_voting)
                    <livewire:poll-comment-vote
                        :key="$comment->id"
                        :comment="$comment"
                    />
                @else
                    <livewire:poll-comment
                        :key="$comment->id"
                        :comment="$comment"
                    />
                @endif
            @endforeach
        </div>

        <div class="my-8 md:ml-22">
            {{ $comments->onEachSide(1)->links() }}
        </div>
    @else
        <div class="mx-auto w-70 mt-12">
            <img src="{{ asset('img/no-polls.svg') }}" alt="No Polls" class="mx-auto mix-blend-luminosity">
            <div class="text-gray-400 text-center font-bold mt-6">No comments yet...</div>
        </div>
    @endif
</div>
