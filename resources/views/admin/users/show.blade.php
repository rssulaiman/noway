@extends('layouts.app', ['title' => 'Profil de ' . $user->full_name])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details de l'utilisateur

                        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="ml-auto btn btn-primary">
                            <i class="fa fa-pen"></i>
                            Editer
                        </a>
                    </div>
                    <div class="card-body">
                        Nom: {{ $user->nom }}
                        <br>
                        Prenom: {{ $user->prenom }}
                        <br>
                        Login: {{ $user->login }}
                        <br>
                        Type: {{ $user->type }}
                        <br>
                        @if ($user->is_etudiant && $user->formation)
                        Formation: <a href="{{ route('admin.formations.show', ['formation' => $user->formation->id]) }}">{{ $user->formation->intitule }}</a>
                        @endif

                        <br/>

                        @php
                            $count = $user->courses->count();
                        @endphp
                        @if ($count && ($user->is_etudiant || $user->is_enseignant))
                            <p>
                                @if ($user->is_etudiant)
                                est inscrit à
                                @else
                                est assigné à
                                @endif
                                {{ $count }} cours
                            </p>
                            <ul>
                            @foreach ($user->courses as $course)
                                <li class="">
                                    <a href="{{ route('admin.courses.show', ['course' => $course->id]) }}">
                                        {{ $course->intitule }}
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
