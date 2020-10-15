@extends("layouts.layout")
@section("content")
    @if(isset($_GET['search']))
        @if(count($posts))
            <h2>Результаты поиска <?php=$_GET["search"]?></h2>
            <?php
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
            ?>
            <p class="lead">Всего найдено {{count($posts)}} {{$posts_count_name}}</p>
        @else
            <h2>По запросу {{ $_GET['search'] }} ничего не найдено.</h2>
            <a href="{{ route("post.index") }}" class="btn btn-outline-primary">Отобразить все посты</a>
        @endif
    @endif
    <div class="row">
        @foreach($posts as $post)
<!--        <div class="col-6 card border-primary "> -->
        <div class="col-6 border-primary ">
            <div class="card">
                <div class="card-header"><h2>{{$post->short_title }}</h2></div>
                <div class="card-body">
                    <div class="card-img" style="background-image: url({{$post->img ?? asset("img/default.jpg")}}) "></div>
                    <div class="card-author">Автор: {{ $post->name }}</div>
                    <a href="#" class="btn btn-outline-primary">Посмотреть пост</a>
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