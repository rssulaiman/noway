@extends('layouts.app', ['title' => "Affichage du cours {$course->intitule}"])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details du cours

                        <div class="ml-auto">

                            <a href="{{ route('admin.plannings.create', ['course_id' => $course->id]) }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter un planning
                            </a>

                            <a href="{{ route('admin.courses.edit', ['course' => $course->id]) }}" class="btn btn-primary">
                                <i class="fa fa-pen"></i>
                                Editer
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        Intitule: {{ $course->intitule }}
                        <br/>
                        Enseignant: @if($course->enseignant)
                        <a href="{{ route('admin.users.show', ['user' => $course->enseignant->id]) }}">
                            {{ $course->enseignant->full_name }}
                        </a>
                        @else
                        Non assigné
                        @endif
                        <br/>
                        @php
                            $count = $course->etudiants->count();
                        @endphp
                        @if ($count)
                        <p>
                            {{ $count }} etudiants inscrits dans ce cours
                        </p>
                        <ul>
                        @foreach ($course->etudiants as $etudiant)
                            <li class="">
                                <a href="{{ route('admin.users.show', ['user' => $etudiant->id]) }}">
                                    {{ $etudiant->full_name }}
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
