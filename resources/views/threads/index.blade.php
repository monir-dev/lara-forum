@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 justify-content-center">
                @include('threads._list')

                <div class="justify-content-center">
                    {{ $threads->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
