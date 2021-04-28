@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header d-flex">
                    @php
                    $title = 'Liste des utilisateurs';
                    $title = $type ? $type == 'enseignant' ? 'Liste des enseignants' : 'Liste des etudiants' : $title;
                    @endphp

                    {{ $title }}


                    <div class="col-md-6 ml-auto">
                        <form action="" method="get" class="form-inline">
                            <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                            <div class="form-group mr-2 mb-2">
                                <select class="form-control" name="type">
                                    <option value="" @if($type == null) selected @endif>Tout le monde</option>
                                    <option value="enseignant" @if($type == 'enseignant') selected @endif>
                                        Enseignants
                                    </option>
                                    <option value="etudiant" @if($type == 'etudiant') selected @endif>
                                        Étudiants
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
                    </div>

                    <div class="ml-auto">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
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
                                <th scope="col">Nom</th>
                                <th scope="col">Login</th>
                                <th scope="col">Role</th>
                                <th scope="col">Formation</th>
                                <th scope="col">Nombre de cours</th>
                                <th scope="col" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->type ? $user->type : 'null' }}</td>
                                <td>{{ $user->formation ? $user->formation->intitule : '' }}</td>
                                <td>{{ $user->courses && !$user->is_admin ? $user->courses->count() : '0' }}</td>
                                <td class="pb-2" style="width: 25%;">
                                    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                        <i class="fa fa-eye"></i>
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                        <i class="fa fa-pen"></i>
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.users.forceDelete', ['user' => $user->id]) }}" method="POST"
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
