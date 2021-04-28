<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Formation;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        $courses = Course::query();
        $active_enseignant = null;

        if ($query_search = $request->get('query')) {
            $courses = $courses->where('intitule', 'LIKE', "%{$query_search}%");
        }

        if ($enseignant_id = $request->get('enseignant_id')) {
            if ($enseignant_id == 'no') {
                $courses = $courses->where('user_id', null);
                $active_enseignant = 'no';
            } else {
                $courses = $courses->where('user_id', $enseignant_id);
                $active_enseignant = User::find($enseignant_id);
            }
        }

        return view('admin.courses.index', [
            'active_enseignant' => $active_enseignant,
            'courses' => $courses->get(),
            'enseignants' => $this->userRepo->getEnseignants(),
            'query' => $query_search
        ]);
    }

    public function create(Request $request)
    {
        $course = new Course;

        if ($active_formation = Formation::find($request->get('formation_id'))) {
            $course->formation_id = $active_formation->id;
        }

        if ($active_user = User::find($request->get('user_id'))) {
            $course->user_id = $active_user->is_enseignant ? $active_user->id : null;
        }

        return view('admin.courses.showForm', [
            'course' => $course,
            'enseignants' => $this->userRepo->getEnseignants(),
            'formations' => Formation::all(),
            'editing' => false
        ]);
    }

    public function store(CourseRequest $request)
    {
        $data = $request->all();
        if (Course::create($data)) {
            return redirect()->route('admin.courses.index')->with(['success' => 'Le cours a bien été ajouté !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
    }

    public function show(Course $course)
    {
        return view('admin.courses.show', ['course' => $course]);
    }

    public function edit(Course $course)
    {
        return view('admin.courses.showForm', [
            'course' => $course,
            'formations' => Formation::all(),
            'enseignants' => $this->userRepo->getEnseignants(),
            'editing' => true
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->all();

        if ($course->update($data)) {
            return redirect()->route('admin.courses.index')->with(['success' => 'Le cours a bien été modifié !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
    }

    public function destroy(Course $course)
    {
        if ($course->delete()) {
            return redirect()->route('admin.courses.index')->with(['success' => 'Le cours a bien ete supprime']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
    }
}
