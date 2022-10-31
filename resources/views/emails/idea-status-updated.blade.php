@component('mail::message')
# Poll Status Updated

The poll: {{ $poll->title }}

has been updated to a status of:

{{ $poll->status->name }}

@component('mail::button', ['url' => route('poll.show', $poll)])
View Poll
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
