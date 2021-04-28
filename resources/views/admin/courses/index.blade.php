@extends('layouts.app', ['title' => 'Liste des cours'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Liste des cours
                        @if ($active_enseignant == 'no')
                         non assignés
                        @elseif ($active_enseignant)
                         de {{ $active_enseignant->full_name }}
                        @endif

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="enseignant_id">
                                        <option value="" @if($active_enseignant == null) selected @endif>Tous les enseignants</option>
                                        <option value="no" @if($active_enseignant == 'no') selected @endif>Non assigné</option>
                                        @foreach ($enseignants as $enseignant)
                                            <option value="{{ $enseignant->id }}" @if($active_enseignant && $active_enseignant != 'no' && $active_enseignant->id == $enseignant->id) selected @endif>
                                                {{ $enseignant->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-primary">
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
                                    <th scope="col">Intitule du cours</th>
                                    <th scope="col">Enseignant assigné</th>
                                    <th scope="col">Nombre d'etudiants</th>
                                    <th scope="col">Intitule de la formation</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->intitule }}</td>
                                    <td class="{{ $course->enseignant ? 'text-primary' : 'text-danger' }}">
                                        @if($course->enseignant)
                                        <a href="{{ route('admin.users.show', ['user' => $course->enseignant->id]) }}">
                                            {{ $course->enseignant->full_name }}
                                        </a>
                                        @else
                                            Non assigné
                                        @endif
                                    </td>
                                    <td>{{ $course->etudiants->count() }}</td>
                                    <td class="{{ $course->formation ? 'text-success' : 'text-danger' }}">
                                        {{ $course->formation ? $course->formation->intitule : 'Non assigné' }}
                                    </td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('admin.courses.show', ['course' => $course->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir
                                        </a>
                                        <a href="{{ route('admin.plannings.index', ['course_id' => $course->id]) }}" class="btn btn-outline-danger btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir les plannings
                                        </a>
                                        <a href="{{ route('admin.courses.edit', ['course' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-pen"></i>
                                            Modifier
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', ['course' => $course->id]) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                                <i class="fa fa-trash-alt"></i>
                                                Supprimer
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
