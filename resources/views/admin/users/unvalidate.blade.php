@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header d-flex">
                    Utilisateurs an attente de validatation

                    <div class="col-md-6 ml-auto">
                        <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="type">
                                        <option value="" @if($type == null) selected @endif>Tout le monde</option>
                                        <option value="enseignant" @if($type == 'enseignant') selected @endif>Enseignants</option>
                                        <option value="etudiant" @if($type == 'etudiant') selected @endif>Étudiants</option>
                                    </select>
                                </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
                    </div>

                    <div class="ml-auto">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                            <i class="fa fa-plus"></i>
                            Ajouter un nouveau utilisateur
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
                                <th scope="col" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->formation_id ? 'etudiant' : 'enseignant' }}</td>
                                <td>{{ $user->formation ? $user->formation->intitule : '' }}</td>
                                <td class="pb-2" style="width: 25%;">
                                    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                        <i class="fa fa-eye"></i>
                                        Voir
                                    </a>
                                    <form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="POST"
                                          class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="type" value="{{ $user->formation_id ? 'etudiant' : 'enseignant' }}"/>
                                        <input type="hidden" name="formation_id" value="{{ $user->formation_id }}"/>
                                        <button type="submit" class="btn btn-outline-success btn-sm mr-1 mb-1">
                                            <i class="fa fa-check"></i>
                                            Accepter
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                            <i class="fa fa-trash-alt"></i>
                                            Refuser
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
