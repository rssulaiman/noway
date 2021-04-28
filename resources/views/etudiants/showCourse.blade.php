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
                            <form action="{{ route('etudiants.courses.update', ['course' => $course->id]) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('PUT')


                                @if (in_array($course->id, $myCourses))
                                    <input type="hidden" name="validate" value="0">
                                    <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                        <i class="fa fa-power-off"></i>
                                        Se desinscrire
                                    </button>
                                @else
                                    <input type="hidden" name="validate" value="1">
                                    <button type="submit" class="btn btn-warning btn-sm mr-1 mb-1">
                                        <i class="fa fa-sign-in-alt"></i>
                                        S'inscrire
                                    </button>
                                @endif
                            </form>
                        </div>

                        @if (in_array($course->id, $myCourses))
                            <div class="ml-auto">
                                <a href="{{ route('etudiants.plannings', ['course_id' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                    <i class="fa fa-clock"></i>
                                    Voir le planning
                                </a>
                            </div>
                        @endif
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
                            @if ($etudiant->id != Auth::user()->id)
                            <li class="">
                                <a href="{{ route('profile.show', ['user' => $etudiant->id]) }}">
                                    {{ $etudiant->full_name }}
                                </a>
                            </li>
                            @endif
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
