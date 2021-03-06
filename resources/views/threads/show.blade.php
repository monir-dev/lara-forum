@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">notifications
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                                <span class="flex">
                                    <img src="{{ $thread->owner->avatar_path }}" alt="{{ $thread->owner->name }}" width="25" height="25" class="mr-1">
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

                    <replies
                        @added="repliesCount++"
                        @removed="repliesCount--"
                    ></replies>
                    {{--{{ $replies->links() }}--}}
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="#">{{ $thread->owner->name }}</a>, and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            </p>
                            <p>
                                <subscribe-button :subscribed="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn" ></subscribe-button>

                                <button class="btn btn-outline-dark" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unloak' : 'lock'"></button>
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
