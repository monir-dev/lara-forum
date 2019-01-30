@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profile', $profileUser->name)  }}">{{ $profileUser->name }}</a> Published a thread: <a
            href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
