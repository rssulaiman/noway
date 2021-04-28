<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanningRequest;
use App\Models\Course;
use App\Models\Planning;
use App\Models\User;
use App\Repositories\CourseRepository;
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
        $active_course = $active_course ? Course::find($active_course) : null;

        $active_user = $request->get('user_id');
        $plannings = $active_user
            ? $this->courseRepo->getUserCoursesPlannings($active_course, $date_debut, $date_fin, User::find($active_user))
            : $this->courseRepo->getCoursesPlannings($active_course, $date_debut, $date_fin);


        return view('admin.plannings.index', [
            'active_user' => $active_user,
            'active_course' => $active_course,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'courses' => Course::all(),
            'plannings' => $plannings
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

        return view('admin.plannings.showForm', [
            'editing' => false,
            'planning' => $planning,
            'courses' => Course::all()
        ]);
    }

    public function store(PlanningRequest $request)
    {
        $data = $request->all();
        if (Planning::create($data)) {
            return redirect()->route('admin.plannings.index')->with(['success' => 'Le planning a bien été ajouté !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
    }

    public function show(Planning $planning)
    {
        return view('admin.plannings.show', ['planning' => $planning]);
    }

    public function edit(Planning $planning)
    {
        return view('admin.plannings.showForm', [
            'editing' => true,
            'planning' => $planning,
            'courses' => Course::all()
        ]);
    }

    public function update(PlanningRequest $request, Planning $planning)
    {
        $data = $request->all();
        $this->setGoodDate($data);

        if ($planning->update($data)) {
            return redirect()->route('admin.plannings.index')
                ->with(['success' => 'Planning modifie avec succes']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
    }

    public function destroy(Planning $planning)
    {
        if ($planning->delete()) {
            return redirect()->route('admin.plannings.index')
                ->with(['success' => 'Planning supprime avec succes']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur innatendue s\'est produite']);
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
