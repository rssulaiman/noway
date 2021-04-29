<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Models\Course;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::all();

        return view('admin.formations.index', ['formations' => $formations]);
    }

    public function create()
    {
        return view('admin.formations.showForm', [
            'courses' => $this->getAvailableCourses(),
            'formation' => new Formation,
            'editing' => false
        ]);
    }

    public function store(FormationRequest $request)
    {
        $data = $request->all();
        if ($formation = Formation::create($data)) {
            foreach ($request->getNewCourses() as $course) {
                $course->formation()->associate($formation->id);
                $course->save();
            }

            return redirect()->route('admin.formations.index')->with(['success' => 'La formation a bien été ajoutée !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function show(Formation $formation)
    {
        return view('admin.formations.show', ['formation' => $formation]);
    }

    public function edit(Formation $formation)
    {
        return view('admin.formations.showForm', [
            'formation' => $formation,
            'courses' => $this->getAvailableCourses($formation->id),
            'editing' => true
        ]);
    }

    public function update(FormationRequest $request, Formation $formation)
    {
        $data = $request->all();
        if ($formation->update($data)) {
            $courses = $request->get('courses') ?? [];
            foreach ($formation->courses as $course) {
                if (!in_array($course->id, $courses)) {
                    $course->etudiants()->detach($formation->etudiants->pluck('id')->toArray());
                    $course->formation()->dissociate();
                    $course->save();
                }
            }

            foreach ($request->getNewCourses() as $course) {
                $course->formation()->associate($formation->id);
                $course->save();
            }

            return redirect()->route('admin.formations.index')
                ->with(['success' => 'La formation a bien été modifiée !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function destroy(Formation $formation)
    {
        if ($formation->delete()) {
            return redirect()->route('admin.formations.index')
                ->with(['success' => 'La formation a bien été supprimée !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function getAvailableCourses($formation_id = null)
    {
        $courses = Course::where('formation_id', null);
        if ($formation_id) {
            $courses = $courses->orWhere('formation_id', $formation_id);
        }

        return $courses->get();
    }
}
