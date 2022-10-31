@component('mail::message')
# A comment was posted on your poll

{{ $comment->user->name }} commented on your poll:

**{{ $comment->poll->title }}**

Comment: {{ $comment->body }}

@component('mail::button', ['url' => route('poll.show', $comment->poll)])
Go to Poll
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
