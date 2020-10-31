@extends("layouts.layout", ["title" => __("messages.post.edit.title", ["post_id" => $post->post_id])])
@section("content")
<form action="{{ route('post.update', ["id"=>$post->post_id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method("PATCH")
    <h3>{{ __("messages.post.edit.header") }}</h3>
    @include("posts.parts.form")
    <input type="submit" value="{{ __("messages.post.edit.button_confirm") }}" class="btn btn-outline-success" onclick="">
</form>
    

@endsection

