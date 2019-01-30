@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profile', $profileUser->name) }}">{{ $profileUser->name }} favorited a reply</a> favorited a
        <a href="{{ $activity->subject->favorited->path() }}">reply</a>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
