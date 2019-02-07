@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('threads._list')

                <div class="justify-content-center">
                    {{ $threads->links() }}
                </div>
            </div>

            <div class="col-md-4">
                @if(count($trending))
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            Trending thread
                        </div>

                        <div class="card-body p-0">
                            <ul class="list-group border-0">
                                @foreach($trending as $thread)
                                    <li class="list-group-item border-left-0 border-right-0 border-bottom-0">
                                        <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
