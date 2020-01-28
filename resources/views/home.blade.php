@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img class="rounded border mr-3" src="{{ auth()->user()->twitter()->profile_image_url_https }}" width="50" alt="">
                        <h3 class="m-0">
                            Hello, <strong>{{ auth()->user()->twitter()->name }}</strong>
                        </h3>
                    </div>
                    
                </div>
            </div>

            <listing></listing>
            

        </div>
    </div>
</div>
@endsection
