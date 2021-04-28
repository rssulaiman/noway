<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\User;

class CourseRequest extends BaseRequest
{
    protected $etudiants_array = [];
    protected $active_course = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $course = $this->sometimes == '' ? null : $this->route('course');
        $this->active_course = $course;
        $formation_id = $this->formation_id;
        $course_id = $course ? $course->id : null;

        return [
            'formation_id' => 'nullable|exists:formations,id',
            'user_id' => ['nullable', 'exists:users,id', function ($attribute, $value, $fail) {
                $user = User::find($value);
                if ($user && !$user->is_enseignant) {
                    $fail('L\'utilisateur renseigne n\'est pas un enseignant');
                }
            }],
            'intitule' => ['required', 'string', function ($attribute, $value, $fail) use ($formation_id, $course_id) {
                $course = Course::where('intitule', $value)
                    ->where('formation_id', $formation_id)->first();

                if ($course && $course->id != $course_id) {
                    $fail('Ce cours existe deja dans cette formation');
                }
            }],
            'etudiants' => ['nullable', function ($attribute, $value, $fail) use ($course) {
                foreach ($value as $item) {
                    $etudiant = User::find($item);
                    if (!$etudiant) {
                        $fail('Un ou plusieurs etudiants inexistant(s)');
                    } else if (!$etudiant->is_etudiant || !$etudiant->formation_id || ($etudiant->formation_id != $course->formation_id)) {
                        $fail('Certains etudiants ne peuvent avoir acces a ce cours');
                    } else {
                        $this->etudiants_array[] = $etudiant;
                    }
                }
            }]
        ];
    }


    public function getNewUsers()
    {
        if ($this->active_course) {
            $course_etudiants = $this->active_course->etudiants()->pluck('id')->toArray();
            return array_filter($this->courses_array, function ($course) use ($course_etudiants) {
                return !in_array($course->id, $course_etudiants);
            });
        }

        return $this->etudiants_array;
    }
}
