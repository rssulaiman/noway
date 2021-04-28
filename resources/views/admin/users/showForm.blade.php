{{-- Create a user --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($user->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => $editing ? 'Modifier un utilisateur' : 'Ajouter un utilisateur'])

@section('content')

    <form action="{{ $editing ? route('admin.users.update', ['user' => $user->id]) : route('admin.users.store') }}" method="post">
        @csrf

        @if($editing)
            @method('put')
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 {{ $user->is_enseignant ? '' : 'mx-auto' }}">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Modifier '.$user->full_name : 'Ajouter un utilisateur' }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom', $user->nom) }}" class="form-control @error('nom') is-invalid @enderror" required>

                                @error('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prenom</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" class="form-control @error('prenom') is-invalid @enderror" required>

                                @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="email" name="login" id="login" value="{{ old('login', $user->login) }}" class="form-control @error('login') is-invalid @enderror" required>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">
                                    {{ __('Type') }}
                                </label>

                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror">
                                    <option value="" @if(!old('type', $user->type) && !$user->formation_id) selected @endif disabled>Choisir un type</option>
                                    <option value="admin" @if(old('type', $user->type) == 'admin') selected @endif>
                                        Admin
                                    </option>
                                    <option value="etudiant" @if(old('type', $user->type) == 'etudiant' || $user->formation_id) selected @endif>
                                        Etudiant
                                    </option>
                                    <option value="enseignant" @if(old('type', $user->type) == 'enseignant') selected @endif>
                                        Enseignant
                                    </option>
                                </select>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="formation_id">
                                    {{ __('Formation') }}
                                    <small>
                                        (Ne selectionner que si l'utilisateur est un etudiant)
                                    </small>
                                </label>

                                <select name="formation_id" id="formation_id"
                                    class="form-control @error('formation_id') is-invalid @enderror">
                                    <option value=""
                                        @if(old('formation_id', $user->formation_id) == null) selected @endif>
                                        Aucune formation
                                    </option>
                                    @foreach($formations as $formation)
                                    <option value="{{ $formation->id }}"
                                        @if(old('formation_id', $user->formation_id) == $formation->id) selected @endif>
                                        {{ $formation->intitule }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('formation_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" @if (!$editing) required @endif>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmer mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" @if (!$editing) required @endif>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" type="submit">
                                    {{ $editing ? 'Modifier' : 'Ajouter' }}
                                </button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($user->is_enseignant || $user->is_etudiant)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $user->formation_id ? 'Inscrire l\'etudiant a des cours ' : 'Ajouter des cours a l\'enseignant' }}
                        </div>
                        <div class="card-body">
                            @include('admin.partials.addCourses', [
                                'item' => $user,
                                'courses' => $user->formation_id ? $user->formation->courses : $courses
                            ])

                            <div class="mb-2">
                                <small>Si le cours n'existe pas,
                                    <strong>
                                        <a href="{{ route('admin.courses.create', ['user_id' => $user->id]) }}">
                                            Ajouter ici
                                        </a>
                                    </strong>
                                </small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
@endsection
