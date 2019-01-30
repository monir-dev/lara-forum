{{--<reply :attributes="{{ $reply }}" inline-template v-cloak>--}}
    {{--<div class="card" id="reply-{{ $reply->id }}">--}}
        {{--<div class="card-header">--}}
            {{--<div class="level">--}}
                {{--<h5 class="flex">--}}
                    {{--<a href="{{ route('profile', $thread->owner) }}">--}}
                        {{--{{ $reply->owner->name }}--}}
                    {{--</a> said {{ $reply->created_at->diffForHumans() }}...--}}
                {{--</h5>--}}
                {{--@if (Auth::check())--}}
                    {{--<div>--}}
                        {{--<Favorite :reply="{{ $reply }}"></Favorite>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="card-body">--}}
            {{--<div v-if="editing">--}}
                {{--<div class="form-group">--}}
                    {{--<textarea name="" id="" class="form-control" rows="2" v-model="body"></textarea>--}}
                {{--</div>--}}
                {{--<button class="btn btn-primary btn-sm" @click="update">Update</button>--}}
                {{--<button class="btn btn-link" @click="editing = false">Cencel</button>--}}
            {{--</div>--}}
            {{--<div v-else v-text="body"></div>--}}
        {{--</div>--}}

        {{--@can('delete', $reply)--}}
            {{--<div class="card-footer d-flex">--}}
                {{--<button class="btn btn-outline-warning btn-sm mr-2" @click="editing = true">Edit</button>--}}
                {{--<button class="btn btn-outline-danger btn-sm mr-2" @click="destroy">Delete</button>--}}
            {{--</div>--}}
        {{--@endcan--}}
    {{--</div>--}}
{{--</reply>--}}
