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
                            <a href="{{ route('enseignants.plannings.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter un planning
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Nom du cours</th>
                                    <th scope="col">Nom d'etudiants inscrits</th>
                                    <th scope="col">Plannings passes</th>
                                    <th scope="col">Plannings a venir</th>
                                    <th scope="col" style="width: 50%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->intitule }}</td>
                                    <td>{{ $course->etudiants->count() }}</td>
                                    <td>{{ $course->stat_datas['passed'] }}</td>
                                    <td>{{ $course->stat_datas['coming'] }}</td>
                                    <td class="pb-2" style="width: 50%;">
                                        <a href="{{ route('enseignants.courses.show', ['course' => $course->id]) }}"
                                            class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir
                                        </a>
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
