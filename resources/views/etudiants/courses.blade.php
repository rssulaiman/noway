@extends('layouts.app', ['title' => 'Liste de mes cours'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Liste de mes cours

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('etudiants.courses.add') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Nom du cours</th>
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
                                        <a href="{{ route('etudiants.plannings', ['course_id' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-clock"></i>
                                            Voir le planning
                                        </a>
                                        <form action="{{ route('etudiants.courses.update', ['course' => $course->id]) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="validate" value="0">
                                            <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                                <i class="fa fa-power-off"></i>
                                                Se desinscrire
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
