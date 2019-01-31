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

                    <Replies
                        @added="repliesCount++"
                        @removed="repliesCount--"
                    ></Replies>
                    {{--{{ $replies->links() }}--}}
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