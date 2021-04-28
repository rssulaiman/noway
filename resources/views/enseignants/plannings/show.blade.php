@extends('layouts.app', ['title' => "Affichage du planning du cours {$planning->course->intitule}"])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details du planning

                        <a href="{{ route('enseignants.plannings.edit', ['planning' => $planning->id]) }}" class="ml-auto btn btn-primary">
                            <i class="fa fa-pen"></i>
                            Editer
                        </a>
                    </div>
                    <div class="card-body">
                        Date de debut: {{ $planning->date_debut }}
                        <br/>
                        Date de fin: {{ $planning->date_fin }}
                        <br/>
                        Cours:
                        <a href="{{ route('enseignants.courses.show', ['course' => $planning->course->id]) }}">
                            {{ $planning->course->intitule }}
                        </a>
                        <br/>
                        Enseignant:
                        @if($planning->course->enseignant)
                            <a href="{{ route('profile.show', ['user' => $planning->course->enseignant->id]) }}">
                                {{ $planning->course->enseignant->full_name }}
                            </a>
                        @else
                            Non assigné
                        @endif
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
