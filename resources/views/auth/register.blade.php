@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register as student') }}</div>

                <div class="card-body">
                    @if ($errors->has('request_type'))
                        <div class="p-2 alert alert-danger">
                            Une erreur inattendue s'est produite
                        </div>
                    @endif

                    @if (count($formations))
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="nom" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>

                                <div class="col-md-6">
                                    <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required autocomplete="nom" autofocus>

                                    @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prenom" class="col-md-4 col-form-label text-md-right">{{ __('Prenom') }}</label>

                                <div class="col-md-6">
                                    <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required autocomplete="prenom">

                                    @error('prenom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="login" class="col-md-4 col-form-label text-md-right">{{ __('Login') }}</label>

                                <div class="col-md-6">
                                    <input id="login" type="email" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login">

                                    @error('login')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="formation_id" class="col-md-4 col-form-label text-md-right">{{ __('Formation') }}</label>
                                <div class="col-md-6">
                                    <select name="formation_id" id="formation_id"
                                            class="form-control @error('formation_id') is-invalid @enderror" required>
                                        <option value="" disabled>Choisir une formation</option>
                                        @foreach($formations as $formation)
                                        <option value="{{ $formation->id }}" @if(old('formation_id') == $formation->id) selected @endif>
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
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mot de passe') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirmer le mot de passe') }}</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <input type="hidden" name="request_type" value="etudiant">
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p>
                            Vous ne pouvez pas vous inscrire a un cour pour le moment, <br>
                            Revenez plus tard
                        </p>
                    @endif
                </div>

                <div class="card-footer">
                    <a href="{{ route('teachers.register') }}">Je suis un enseignant</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
