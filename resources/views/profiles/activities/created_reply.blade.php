@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profile', $profileUser->name)  }}">{{ $profileUser->name }}</a> replied to <a
            href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
