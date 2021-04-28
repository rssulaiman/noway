@extends('layouts.app', ['title' => 'Liste des formations'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Liste des Formations

{{--                        <div class="col-md-6 ml-auto">--}}
{{--                            <form action="" method="get" class="form-inline">--}}
{{--                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>--}}
{{--                                <button type="submit" class="btn btn-primary mb-2">Search</button>--}}
{{--                            </form>--}}
{{--                        </div>--}}

                        <div class="ml-auto">
                            <a href="{{ route('admin.formations.create') }}" class="btn btn-outline-primary">
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
                                    <th scope="col">Intitule de la formation</th>
                                    <th scope="col">Nombre de cours</th>
                                    <th scope="col">Nombre d'etudiants</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($formations as $formation)
                                <tr>
                                    <td>{{ $formation->intitule }}</td>
                                    <td>{{ $formation->courses->count() }}</td>
                                    <td>{{ $formation->etudiants->count() }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('admin.formations.show', ['formation' => $formation->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir
                                        </a>
                                        <a href="{{ route('admin.formations.edit', ['formation' => $formation->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-pen"></i>
                                            Modifier
                                        </a>
                                        <form action="{{ route('admin.formations.destroy', ['formation' => $formation->id]) }}" method="POST"
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
