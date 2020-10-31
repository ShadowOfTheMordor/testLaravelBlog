
<!doctype html>
<!--<html lang="ru">-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>{{ $title }}</title>
        <link rel="stylesheet" href="{{asset("css/app.css") }}">
        <link rel="stylesheet" href="{{asset("css/style.css") }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("img/favicons/apple-touch-icon.png") }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("img/favicons/favicon-32x32.png") }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("img/favicons/favicon-16x16.png") }}">
        <link rel="shortcut icon" type="image/png" sizes="16x16" href="{{ asset("img/favicons/favicon.ico") }}">
        <link rel="manifest" href="{{ asset("img/favicons/site.webmanifest") }}"> 
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <div class="container-fluid collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="col-md-4 navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route("post.index") }}">{{ __("messages.app.navbar.home_page") }}<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active offset-md-2">
                <a class="nav-link" href="{{ route("post.create") }}">{{ __("messages.app.navbar.create_post") }}<span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="{{ route('post.index') }}">
            <input class="form-control mr-sm-2" name="search" type="search" placeholder="{{ __("messages.app.navbar.search") }}" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">{{ __("messages.app.navbar.search_button") }}</button>
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
        <ul class="navbar-nav ml-auto">
        @php 
            $locale = Session::get("app.locale");
            //echo "locale is ".$locale; 
        @endphp
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                @switch($locale)
                @case('ru')
                    <img src="{{ asset("img/lang/ru.png") }}"> {{ __("messages.app.navbar.language.russian") }}
                @break
                @case('us')
                    <img src="{{ asset("img/lang/us.png") }}"> {{ __("messages.app.navbar.language.english") }}
                @break
                @case('de')
                    <img src="{{ asset("img/lang/de.png") }}"> {{ __("messages.app.navbar.language.german") }}
                @break
                @case('in')
                    <img src="{{ asset("img/lang/in.png") }}"> {{ __("messages.app.navbar.language.hindi") }}
                @break
                @case('fr')
                    <img src="{{ asset("img/lang/fr.png") }}"> {{ __("messages.app.navbar.language.french") }}
                @break
                @case('es')
                    <img src="{{ asset("img/lang/es.png") }}"> {{ __("messages.app.navbar.language.spanish") }}
                @break
                @case('ch')
                    <img src="{{ asset("img/lang/ch.png") }}"> {{ __("messages.app.navbar.language.chinese") }}
                @break
                @default
                    <img src="{{ asset("img/lang/ru.png") }}"> {{ __("messages.app.navbar.language.russian") }}
                @endswitch
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "ru"]) }}"><img src="{{ asset("img/lang/ru.png") }}"> {{ __("messages.app.navbar.language.russian") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "us"]) }}"><img src="{{ asset("img/lang/us.png") }}"> {{ __("messages.app.navbar.language.english") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "de"]) }}"><img src="{{ asset("img/lang/de.png") }}"> {{ __("messages.app.navbar.language.german") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "in"]) }}"><img src="{{ asset("img/lang/in.png") }}"> {{ __("messages.app.navbar.language.hindi") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "fr"]) }}"><img src="{{ asset("img/lang/fr.png") }}"> {{ __("messages.app.navbar.language.french") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "es"]) }}"><img src="{{ asset("img/lang/es.png") }}"> {{ __("messages.app.navbar.language.spanish") }}</a>
                    <a class="dropdown-item" href="{{ route("language.switch",["locale" => "ch"]) }}"><img src="{{ asset("img/lang/ch.png") }}"> {{ __("messages.app.navbar.language.chinese") }}</a>
                </div>
            </li>
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