@extends("layouts.layout", ["title" => __("messages.errors.404.title")])

@section("content")
    <div class="card">
        <h2 class="card-header">{{ __("messages.errors.404.header") }}</h2>
<!--        <img src="{{ asset("img/sith_happens.jpg") }}" alt="sith"/> -->
        <div class="card-img card-img-max" style="background-image: url('{{ asset("img/sith_happens.jpg") }}') "></div>
    </div>
    <a href="{{ route("post.index") }}" class="btn btn-outline-primary">{{ __("messages.errors.404.button_return") }}</a>
@endsection