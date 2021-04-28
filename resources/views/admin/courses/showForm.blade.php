{{-- Create a course --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($course->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => $editing ? 'Modifier le cours' : 'Ajouter un cours'])

@section('content')
    <form action="{{ $editing ? route('admin.courses.update', ['course' => $course->id]) : route('admin.courses.store') }}" method="post">
        @csrf

        @if($editing)
            @method('put')
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 {{ $editing ? '' : 'mx-auto' }}">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Modifier le cours' : 'Ajouter un cours' }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intitule">Intitule</label>
                                <input type="text" name="intitule" id="intitule" value="{{ old('intitule', $course->intitule) }}" class="form-control @error('intitule') is-invalid @enderror" required>

                                @error('intitule')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="formation_id">
                                    {{ __('Formation') }}
                                </label>

                                <select name="formation_id" id="formation_id"
                                    class="form-control @error('formation_id') is-invalid @enderror">
                                    <option value="" @if(old('formation_id', $course->formation_id) == null) selected @endif>Aucune formation</option>
                                    @foreach($formations as $formation)
                                    <option value="{{ $formation->id }}" data-etudiants="{{ $formation->etudiants }}"
                                        @if(old('formation_id', $course->formation_id) == $formation->id) selected @endif>
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
                                <label for="user_id">
                                    {{ __('Enseignant') }}
                                </label>

                                <select name="user_id" id="user_id"
                                    class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="" @if(old('user_id', $course->user_id) == null) selected @endif>Aucun enseignant</option>
                                    @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" @if(old('user_id', $course->user_id) == $enseignant->id) selected @endif>
                                        {{ $enseignant->full_name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                @if ($editing)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Ajouter des cours a la formation
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="etudiants">Choisir les etudiants</label>
                                @php
                                    $etudiants_array = $course->etudiants->pluck('id')->toArray();
                                @endphp
                                <select id="etudiants" name="etudiants[]" class="form-control" multiple>
                                    <option value="" disabled>Choisir les etudiants</option>
                                    @foreach ($course->formation->etudiants as $etudiant)
                                    <option value="{{ $etudiant->id }}"
                                        @if (in_array($etudiant->id, old('etudiants', $etudiants_array))) selected @endif>{{ $etudiant->full_name }}</option>
                                    @endforeach
                                </select>
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
