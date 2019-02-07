@forelse($threads as $thread)
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <div class="level">
                <h4 class="flex">
                    <a href="{{ $thread->path() }}">
                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong>{{ $thread->title }}</strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>

                    <span class="ml-3">
                        Posted by : <a href="{{ url('profiles/' . $thread->owner->name) }}">{{ $thread->owner->name }}</a>
                    </span>
                </h4>

                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            {{ $thread->body }}
        </div>

        <div class="card-footer">
            {{--{{ $thread->visits()->count() }} visits--}} {{-- for redis--}}
            {{ $thread->visits_count }} visits
        </div>
    </div>
@empty
    <p>There are no relevent record at this time.</p>
@endforelse