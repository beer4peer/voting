<nav class="hidden md:flex items-center justify-between text-gray-400 text-xs">
    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li><a wire:click.prevent="setStatus('All')" href="{{ route('poll.index', ['status' => 'All']) }}" class="border-b-4 pb-3 hover:border-blue @if ($status === 'All') border-blue text-gray-900 @endif">All Polls ({{ $statusCount['all_statuses'] }})</a></li>
    </ul>

    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li><a wire:click.prevent="setStatus('Accepted')" href="{{ route('poll.index', ['status' => 'Implemented']) }}" class=" transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'Accepted') border-blue text-gray-900 @endif">Accepted ({{ $statusCount['accepted'] }})</a></li>
        <li><a wire:click.prevent="setStatus('Rejected')" href="{{ route('poll.index', ['status' => 'Rejected']) }}" class=" transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'Rejected') border-blue text-gray-900 @endif">Rejected ({{ $statusCount['rejected'] }})</a></li>
    </ul>
</nav>
