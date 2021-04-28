@extends('layouts.app', ['title' => "Affichage de la formation {$formation->intitule}"])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details de la formation

                        <a href="{{ route('admin.formations.edit', ['formation' => $formation->id]) }}" class="ml-auto btn btn-primary">
                            <i class="fa fa-pen"></i>
                            Editer
                        </a>
                    </div>
                    <div class="card-body">
                        Intitule: {{ $formation->intitule }}
                        <br/>
                        @php
                            $count = $formation->courses->count();
                        @endphp
                        @if ($count)
                        <p>
                            {{ $count }} cours inclu(s) dans cette formation
                        </p>
                        <ul>
                        @foreach ($formation->courses as $course)
                            <li class="">
                                <a href="{{ route('admin.courses.show', ['course' => $course->id]) }}">
                                    {{ $course->intitule }}
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
