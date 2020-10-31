@extends("layouts.layout", ["title" => __("messages.post.show.title",["post_id" => $post->post_id, "post_title" => $post->title]) ])
@section("content")
    <div class="row">
        <div class="col-12 border-primary ">
            <div class="card">
                <div class="card-header"><h2>{{$post->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img card-img-max" style="background-image: url('{{$post->img ? asset($post->img) : asset('img/default.jpg')}}') "></div>
                    <div class="card-description">{{ __("messages.post.show.description",["post_description" => $post->description]) }}</div>
                    <div class="card-author">{{ __("messages.post.show.author",["post_author" => $post->name]) }}</div>
                    <div class="card-date">{{ __("messages.post.show.created_at",["post_created_at" => $post->created_at->diffForHumans()]) }}</div>
                    <div class="card-btn">
                        <a href="{{ route("post.index") }}" class="btn btn-outline-primary">{{ __("messages.post.show.button_homepage") }}</a>
                        <a href="{{ route("post.edit", ["id"=>$post->post_id]) }}" class="btn btn-outline-success">{{ __("messages.post.show.button_edit") }}</a>
                        <form action="{{ route("post.destroy", ["id"=>$post->post_id]) }}" method="post" onsubmit=" if (confirm('{{ __("messages.post.show.button_delete_confirm") }}')) {return true} else {return false}">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger" value="{{ __("messages.post.show.button_delete") }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection