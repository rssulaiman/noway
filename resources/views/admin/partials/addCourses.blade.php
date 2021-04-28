@php
    $courses_array = $item->courses->pluck('id')->toArray();
@endphp

<div class="form-group">
    <label for="courses">Choisir les cours</label>
    <select id="courses" name="courses[]" class="form-control @error('courses') is-invalid @enderror" multiple>
        @foreach ($courses as $course)
        <option value="{{ $course->id }}"
            @if (in_array($course->id, old('courses', $courses_array))) selected @endif>
            {{ $course->intitule }}
        </option>
        @endforeach
    </select>

    @error('courses')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
