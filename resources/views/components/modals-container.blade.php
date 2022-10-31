@can('update', $poll)
    <livewire:edit-poll :poll="$poll" />
@endcan

@can('delete', $poll)
    <livewire:delete-poll :poll="$poll" />
@endcan

@auth
    <livewire:mark-poll-as-spam :poll="$poll" />
@endauth

@admin
    <livewire:mark-poll-as-not-spam :poll="$poll" />
@endadmin

@auth
    <livewire:edit-comment />
@endauth

@auth
    <livewire:delete-comment />
@endauth

@auth
    <livewire:mark-comment-as-spam />
@endauth

@admin
    <livewire:mark-comment-as-not-spam />
@endadmin
