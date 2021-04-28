@extends('layouts.app', ['title' => 'Ajouter des cours'])

@php
    $myCourses = Auth::user()->courses->pluck('id')->toArray();
@endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Liste des cours

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="unsubscribe">
                                        <option value="" @if($unsubscribe == null || $unsubscribe == 0) selected @endif>Tous les cours</option>
                                        <option value="1" @if($unsubscribe == '1') selected @endif>
                                            Non inscrits
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('etudiants.courses') }}" class="btn btn-outline-primary">
                                <i class="fa fa-list"></i>
                                Retourner a la liste de mes cours
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->intitule }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('etudiants.courses.show', ['course' => $course->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir
                                        </a>
                                        @if (in_array($course->id, $myCourses))
                                            <a href="{{ route('etudiants.plannings', ['course_id' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                                <i class="fa fa-clock"></i>
                                                Voir le planning
                                            </a>
                                        @endif

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
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-2">
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
