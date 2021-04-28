@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update profile') }}</div>

                <div class="card-body">
                    @if ($errors->has('request_type'))
                        <div class="p-2 alert alert-danger">
                            Une erreur inattendue s'est produite
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.edit') }}">
                        @csrf

                        @method('put')

                        <div class="form-group row">
                            <label for="nom" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>

                            <div class="col-md-6">
                                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ $user->nom }}" required autocomplete="nom" autofocus>

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
                                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ $user->prenom }}" required autocomplete="prenom">

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
                                <input id="login" type="email" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ $user->login }}" required autocomplete="login">

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

{{--                            <div class="form-group row">--}}
{{--                                <label for="formation_id" class="col-md-4 col-form-label text-md-right">{{ __('Formation') }}</label>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <select name="formation_id" id="formation_id"--}}
{{--                                            class="form-control @error('formation_id') is-invalid @enderror" required>--}}
{{--                                        <option value="" disabled>Choisir une formation</option>--}}
{{--                                        @foreach($formations as $formation)--}}
{{--                                        <option value="{{ $formation->id }}" @if($user->formation_id == $formation->id) selected @endif>--}}
{{--                                            {{ $formation->intitule }}--}}
{{--                                        </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}

{{--                                    @error('formation_id')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                            <strong>{{ $message }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('Mot de passe actuel') }}</label>

                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password">

                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirmer le nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <input type="hidden" name="request_type" value="etudiant">
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Modifier') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
