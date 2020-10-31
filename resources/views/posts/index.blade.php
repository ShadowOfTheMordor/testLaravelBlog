@extends("layouts.layout", ["title" => __("messages.post.index.title")])
@section("content")
    @if(isset($_GET['search']))
        @if(count($posts))
            <h2>{{ __("messages.post.index.search_results",["search_string" => $_GET["search"]]) }}</h2>
            <?php
/*
            $posts_count=count($posts);
                $posts_count_name="";
                if ($posts_count==1)
                    $posts_count_name="пост";
                elseif($posts_count>1&&$posts_count<5)
                {
                    $posts_count_name="поста";
                }
                else
                {
                    $posts_count_name="постов";
                
                }
 * 
 */
            ?>
            <p class="lead">{{ trans_choice("messages.post.index.search_results_found", count($posts),["post_count" => count($posts)]) }}</p>
        @else
            <h2>{{ __("messages.post.index.search_results_empty",["search_string" => $_GET["search"]]) }}</h2>
            <a href="{{ route("post.index") }}" class="btn btn-outline-primary">{{ __("messages.post.index.search.button_return") }}</a>
        @endif
    @endif
    <div class="row">
        @foreach($posts as $post)
<!--        <div class="col-6 card border-primary "> -->
        <div class="col-6 border-primary ">
            <div class="card">
                <div class="card-header"><h2>{{$post->short_title }}</h2></div>
                <div class="card-body">
                    <div class="card-img" style="background-image: url('{{$post->img ? asset($post->img) : asset('img/default.jpg')}}') "></div>
                    <div class="card-author">{{ __("messages.post.index.element.author",["post_author" => $post->name]) }}</div>
                    <a href="{{ route("post.show", ["id" => $post->post_id]) }}" class="btn btn-outline-primary">Посмотреть пост</a>
<!--                <div class="card-img">
                    <img src="{{$post->img ?? asset("img/default.jpg")}}" width="100%" height="100%"/>
                </div>-->
    


<!--                         {{$post->description}} -->
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if(!isset($_GET['search']))
        {{$posts->links()}}
    @endif

    <?php
/*    if(!isset($_GET['search']))
        echo $posts->links();
    else
        echo "THIS IS WORKING ".$_GET['search']."  -->";*/
    ?>

@endsection