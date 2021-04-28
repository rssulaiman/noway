@extends('layouts.app', ['title' => "Affichage du cours {$course->intitule}"])

@php
    $myCourses = Auth::user()->courses->pluck('id')->toArray();
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details du cours

                        <div class="ml-auto">
                            <a href="{{ route('enseignants.plannings.index', ['course_id' => $course->id]) }}"
                                class="btn btn-secondary btn-sm mr-1 mb-1">
                                <i class="fa fa-clock"></i>
                                Voir le planning
                            </a>

                            <a href="{{ route('enseignants.plannings.create', ['course_id' => $course->id]) }}"
                                class="btn btn-warning btn-sm mr-1 mb-1">
                                <i class="fa fa-plus"></i>
                                Ajouter un planning
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        Intitule: {{ $course->intitule }}
                        <br/>
                        Enseignant: @if($course->enseignant)
                        <a href="{{ route('profile.show', ['user' => $course->enseignant->id]) }}">
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
                                <a href="{{ route('profile.show', ['user' => $etudiant->id]) }}">
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
