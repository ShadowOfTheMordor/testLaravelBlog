
<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>{{ $title }}</title>
        <link rel="stylesheet" href="{{asset("css/app.css") }}">
        <link rel="stylesheet" href="{{asset("css/style.css") }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("img/apple-touch-icon.png") }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("img/favicon-32x32.png") }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("img/favicon-16x16.png") }}">
        <link rel="shortcut icon" type="image/png" sizes="16x16" href="{{ asset("img/favicon.ico") }}">
        <link rel="manifest" href="{{ asset("img/site.webmanifest") }}"> 
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">

 <div class="container collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="col-6 navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route("post.index") }}">Главная<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active offset-3">
        <a class="nav-link" href="{{ route("post.create") }}">Создать пост<span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="{{ route('post.index') }}">
      <input class="form-control mr-sm-2" name="search" type="search" placeholder="Найти пост..." aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
    </form>
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('messages.auth.Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('messages.auth.Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('messages.auth.Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
  </div>
</nav>

<?php
//    echo "<pre>";
//    var_dump($posts);
//    echo "</pre>";
?>

<div class="container">
    @if($errors->any())
        @foreach($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $error }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
        @endforeach
    @endif
<!--
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
-->

    @if(Session::has("success"))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get("success") }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @yield("content")
</div>


<!--</nav> -->

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    <script>
/*
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });
    function getPosts(page) {
        $.ajax({
            url : '?page=' + page,
            dataType: 'json',
        }).done(function (data) {
            console.log(data);
            $('.posts').html(data);
            location.hash = page;
        }).fail(function () {
            alert('Posts could not be loaded.');
        });
    }
     * 
 */
    </script>

</body>
</html>