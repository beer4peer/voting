<div
    id="comment-{{ $comment->id }}"
    class="relative flex transition duration-500 ease-in mt-4"
>
    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
        <div class="text-gray-900">{{ $comment->user->name }} has voted!</div>
        <div>&bull;</div>
        <div>{{ $comment->created_at->diffForHumans() }}</div>
    </div>
</div> <!-- end comment-container -->
