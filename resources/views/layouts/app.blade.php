<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $title ?? 'My App' }}
    </title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('fontawesome-free/css/all.min.css') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if(Auth::user())

                            @if(Auth::user()->isEtudiant)

                                <li class="nav-item">
                                    <a href="{{ route('etudiants.index') }}"
                                        class="nav-link @if (request()->is('etudiants/home')) active @endif">
                                        Dashboard
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('etudiants.courses') }}"
                                        class="nav-link @if (request()->is('etudiants/courses*')) active @endif">
                                        Cours
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('etudiants.plannings') }}"
                                        class="nav-link @if (request()->is('etudiants/plannings*')) active @endif">
                                        Plannings
                                    </a>
                                </li>

                            @elseif(Auth::user()->isEnseignant)

                                <li class="nav-item">
                                    <a href="{{ route('enseignants.index') }}"
                                        class="nav-link @if (request()->is('enseignants/home')) active @endif">
                                        Dashboard
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('enseignants.courses') }}"
                                        class="nav-link @if (request()->is('enseignants/courses*')) active @endif">
                                        Cours
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('enseignants.plannings.index') }}"
                                    class="nav-link @if (request()->is('enseignants/plannings*')) active @endif">
                                        Plannings
                                    </a>
                                </li>

                            @elseif(Auth::user()->isAdmin)

                                <li class="nav-item">
                                    <a href="{{ route('admin.index') }}"
                                    class="nav-link @if (request()->is('admin')) active @endif">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link @if (request()->is('admin/users*') && !request()->is('admin/users/unvalidate')) active @endif">
                                        Users
                                    </a>
                                </li>

                                @if (isset($badge) && $badge > 0)

                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.unvalidate') }}"
                                        class="nav-link @if (request()->is('admin/users/unvalidate')) active @endif">
                                            Demandes
                                            <span class="badge badge-success">{{ $badge }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a href="{{ route('admin.formations.index') }}"
                                    class="nav-link @if (request()->is('admin/formations*')) active @endif">
                                        Formations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.courses.index') }}"
                                    class="nav-link @if (request()->is('admin/courses*')) active @endif">
                                        Cours
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.plannings.index') }}"
                                    class="nav-link @if (request()->is('admin/plannings*')) active @endif">
                                        Plannings
                                    </a>
                                </li>
                            @endif

                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link @if (request()->is('login')) active @endif"
                                       href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link @if (request()->is('*register')) active @endif"
                                       href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->full_name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
