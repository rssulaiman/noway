{{-- Create a planning --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($planning->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => $editing ? 'Modifier un planning' : 'Ajouter un planning'])

@section('content')

    <form action="{{ $editing ? route('enseignants.plannings.update', ['planning' => $planning->id]) : route('enseignants.plannings.store') }}" method="post">
        @csrf

        @if($editing)
            @method('put')
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Modifier planning du '.$planning->date_debut : 'Ajouter un planning' }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="debut_date">Date de debut</label>
                                        <input type="date" placeholder="dd/mm/yyyy" name="debut_date" id="debut_date" value="{{ old('debut_date', $planning->debut_date) }}" class="form-control @error('debut_date') is-invalid @enderror" required>

                                        @error('debut_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="debut_heure">Heure de debut</label>
                                        <input type="time" name="debut_heure" id="debut_heure" value="{{ old('debut_heure', $planning->debut_heure) }}" class="form-control @error('debut_heure') is-invalid @enderror" required>

                                        @error('debut_heure')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fin_date">
                                            Date de fin
                                            <small>Laisser vide si la date est equivalente a la date de debut</small>
                                        </label>
                                        <input type="date" placeholder="dd/mm/yyyy" name="fin_date" id="fin_date" value="{{ old('fin_date', $planning->fin_date) }}" class="form-control @error('fin_date') is-invalid @enderror">

                                        @error('fin_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="fin_heure">Heure de fin</label>
                                        <input type="time" name="fin_heure" id="fin_heure" value="{{ old('fin_heure', $planning->fin_heure) }}" class="form-control @error('fin_heure') is-invalid @enderror" required>

                                        @error('fin_heure')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cours_id">
                                    {{ __('Cours') }}
                                </label>

                                <select name="cours_id" id="cours_id"
                                    class="form-control @error('cours_id') is-invalid @enderror" required>
                                    <option value="" disabled
                                        @if(old('cours_id', $planning->cours_id) == null) selected @endif>
                                        Choisir un cours
                                    </option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        @if(old('cours_id', $planning->cours_id) == $course->id) selected @endif>
                                        {{ $course->intitule }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('cours_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" type="submit">
                                    {{ $editing ? 'Modifier' : 'Ajouter' }}
                                </button>
                                @if ($editing)
                                    <a href="{{ route('enseignants.plannings.index') }}" class="btn btn-secondary">
                                        Annuler
                                    </a>
                                @else
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
