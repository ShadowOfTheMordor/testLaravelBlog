@extends("layouts.layout", ["title" => __("messages.post.create.title")])
@section("content")
<form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <h3>{{ __("messages.post.create.header") }}</h3>
    @include("posts.parts.form")
    <input type="submit" value="{{ __("messages.post.create.button_confirm") }}" class="btn btn-outline-success" onclick="">
</form>
    

@endsection

