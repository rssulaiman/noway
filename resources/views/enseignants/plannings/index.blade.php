@extends('layouts.app', ['title' => 'Liste de mes plannings'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Liste de mes plannings
                        @if ($active_course)
                         du cours {{ $active_course->intitule }}
                        @endif

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <div class="form-group">
                                    <label for="date_debut">Date debut</label>
                                    <input type="date" placeholder="dd/mm/yyyy" id="date_debut" name="date_debut"
                                        class="form-control mx-2 mb-2 @error('date_debut') is-invalid @endif"
                                        value="{{ old('date_debut', $date_debut ?? '') }}"/>
                                    @error('date_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="date_fin">Date Fin</label>
                                    <input type="date" placeholder="dd/mm/yyyy" id="date_fin" name="date_fin"
                                        class="form-control mx-2 mb-2  @error('date_fin') is-invalid @endif"
                                        value="{{ old('date_fin', $date_fin ?? '') }}"/>
                                    @error('date_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="course_id">
                                        <option value="" @if($active_course == null) selected @endif>Tous les courses</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" @if($active_course && $active_course->id == $course->id) selected @endif>
                                                {{ $course->intitule }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('enseignants.plannings.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Ajouter un planning
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Nom du cours</th>
                                    <th scope="col">Date de debut</th>
                                    <th scope="col">Date de fin</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($plannings as $planning)
                                <tr>
                                    <td>{{ $planning->course->intitule }}</td>
                                    <td>{{ $planning->date_debut }}</td>
                                    <td>{{ $planning->date_fin }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('enseignants.plannings.show', ['planning' => $planning->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir
                                        </a>
                                        <a href="{{ route('enseignants.plannings.edit', ['planning' => $planning->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-pen"></i>
                                            Modifier
                                        </a>
                                        <form action="{{ route('enseignants.plannings.destroy', ['planning' => $planning->id]) }}" method="POST"
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
