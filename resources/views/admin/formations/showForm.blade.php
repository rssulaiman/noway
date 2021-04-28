{{-- Create a formation --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($formation->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => 'Ajouter un utilisateur'])

@section('content')

    <div class="container">
        <form action="{{ $editing ? route('admin.formations.update', ['formation' => $formation->id]) : route('admin.formations.store') }}" method="post">
            @csrf
            @if($editing)
                @method('put')
            @endif

            <div class="row">
                <div class="col-md-8 {{ $editing ? '' : 'mx-auto' }}">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Modifier une formation' : 'Creer une formation' }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intitule">Intitule</label>
                                <input type="text" name="intitule" id="intitule" value="{{ old('intitule', $formation->intitule) }}"
                                    class="form-control @error('intitule') is-invalid @enderror" placeholder="Intitule" required>

                                @error('intitule')
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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Ajouter des cours a la formation
                        </div>
                        <div class="card-body">
                            @include('admin.partials.addCourses', ['item' => $formation])
                            <div class="mb-2">
                                <small>Si le cours n'existe pas, <strong><a href="{{ route('admin.courses.create', ['formation_id' => $formation->id]) }}">Ajouter ici</a></strong></small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
