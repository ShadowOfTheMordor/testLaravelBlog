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
