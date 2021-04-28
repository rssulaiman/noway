<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanningRequest;
use App\Models\Course;
use App\Models\Planning;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    /**
     * @var CourseRepository
     */
    private $courseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function index(Request $request)
    {
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => array('nullable', 'date', $date_debut && $date_fin ? 'after_or_equal:date_debut' : '')
        ]);

        $active_course = $request->get('course_id');
        if ($active_course) {
            $active_course = Course::find($active_course);
            if (!$active_course || $active_course->user_id != $request->user()->id) {
                $active_course = null;
            }
        }

        return view('enseignants.plannings.index', [
            'active_course' => $active_course,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get(),
            'plannings' => $this->courseRepo->getUserCoursesPlannings($active_course, $date_debut, $date_fin)
        ]);
    }

    public function create(Request $request)
    {
        $planning = new Planning;

        if ($course_id = $request->get('course_id')) {
            if (Course::find($course_id)) {
                $planning->cours_id = $course_id;
            }
        }

        return view('enseignants.plannings.showForm', [
            'editing' => false,
            'planning' => $planning,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get()
        ]);
    }

    public function store(PlanningRequest $request)
    {
        $data = $request->all();
        $this->setGoodDate($data);

        $planning = Planning::create($data);

        if ($planning) {
            return redirect()->route('enseignants.plannings.index')->with(['success' => 'Planning ajoute avec succes']);
        }

        return redirect()->back()->with(['error' => 'Une erreur innatenue s\'est produite']);
    }

    public function show(Planning $planning)
    {
        return view('enseignants.plannings.show', [
            'planning' => $planning,
        ]);
    }

    public function edit(Request $request, Planning $planning)
    {
        return view('enseignants.plannings.showForm', [
            'editing' => true,
            'planning' => $planning,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get()
        ]);
    }

    public function update(PlanningRequest $request, Planning $planning)
    {
        $data = $request->all();
        $this->setGoodDate($data);

        if ($planning->update($data)) {
            return redirect()->route('enseignants.plannings.index')
                ->with(['success' => 'Planning modifie avec succes']);
        }

        return redirect()->back()->with(['error' => 'Une erreur innatenue s\'est produite']);
    }

    public function destroy(Planning $planning)
    {
        if ($planning->delete()) {
            return redirect()->route('enseignants.plannings.index')
                ->with(['success' => 'Planning supprime avec succes']);
        }

        return redirect()->back()->with(['error' => 'Une erreur innatenue s\'est produite']);
    }

    /**
     * @param array $data
     */
    public function setGoodDate(array &$data)
    {
        $data['date_debut'] = $data['debut_date'] . ' ' . $data['debut_heure'];
        $data['date_fin'] = ($data['fin_date'] ?? $data['debut_date']) . ' ' . $data['fin_heure'];
    }
}
