@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                                <span class="flex">
                                    <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->name }}</a> posted: {{ $thread->title }}
                                </span>

                                @can('update', $thread)
                                    <form method="POST" action="{{ url($thread->path()) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <p>{{ $thread->body }}</p>
                        </div>
                    </div>

                    <Replies :data="{{ $thread->replies }}" @removed="repliesCount--"></Replies>
                    <br>
                    {{ $replies->links() }}

                    @if(auth()->check())
                        <br>
                        <form method="POST" action="{{ $thread->path().'/replies' }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="body" id="body" class="form-control" rows="4" placeholder="Have something to say..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post</button>
                        </form>
                    @else
                        <br>
                        <p class="text-center">Please <a href="{{route('login')}}">Sign in</a> to participate in this disscussion.</p>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="#">{{ $thread->owner->name }}</a>, and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
<script>
    import Replies from "../../js/components/Replies";
    export default {
        components: {Replies}
    }
</script>
