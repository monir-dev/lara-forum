@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="page-header">
                    <h2>{{ $profileUser->name }}</h2>
                </div>

                @forelse($activities as $date => $records)
                    <div class="page-header"><h4>{{ $date }}</h4></div>
                    @foreach($records as $activity)
                        @if(view()->exists("profiles.activities.$activity->type"))
                            @include("profiles.activities.$activity->type")
                        @endif
                        <br>
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse
                {{--{{ $threads->links() }}--}}
            </div>
        </div>
    </div>
@endsection
