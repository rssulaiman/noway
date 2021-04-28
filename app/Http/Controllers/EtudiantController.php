<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Etudiant;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{

    /**
     * @var CourseRepository
     */
    private $CourseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->middleware(['auth', 'is_etudiant']);
        $this->courseRepo = $courseRepo;
    }

    public function index(Request $request)
    {
        return $this->view('etudiants.index', [
            'data' => $request->user()->courses
        ]);
    }

    public function show_course(Request $request, Course $course)
    {
        return view('etudiants.showCourse', [
            'course' => $course
        ]);
    }

    public function show_my_courses(Request $request)
    {
        $courses = $request->user()->courses();

        if ($query_string = $request->get('query')) {
            $courses = $courses->where('intitule', 'LIKE', '%' . $query_string . '%');
        }

        return view('etudiants.courses', [
            'query' => $query_string,
            'courses' => $courses->paginate(20)
        ]);
    }

    public function show_all_courses(Request $request)
    {
        $unsubscribe = $request->get('unsubscribe');

        if ($unsubscribe == 1) {
            $courses = $this->courseRepo->getOthersFormationCourses($request->user()->formation_id);
        } else {
            $courses = $this->courseRepo->getFormationCourses($request->user()->formation_id);
        }

        if ($query_string = $request->get('query')) {
            $courses = $courses->where('intitule', 'LIKE', '%' . $query_string . '%');
        }

        return view('etudiants.showFormCourses', [
            'unsubscribe' => $unsubscribe ? true : false,
            'query' => $query_string,
            'courses' => $courses->paginate(20)
        ]);
    }

    public function update_course(Request $request, Course $course)
    {
        $request->validate([
            'validate' => 'required'
        ]);

        $validate = $request->get('validate');
        if ($validate == 0) {
            $course->etudiants()->detach($request->user()->id);
            $message = 'Desinscription effectue avec succes';
        } elseif ($validate == 1) {
            $course->etudiants()->attach($request->user()->id);
            $message = 'Inscription effectue avec succes';
        }

        if ($course->save()) {
            return redirect()->back()->with(['success' => $message]);
        }

        return redirect()->back()->with(['error' => 'Une erreur innatendue s\'est produite']);
    }

    public function show_plannings(Request $request)
    {
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => array('nullable', 'date', $date_debut && $date_fin ? 'after_or_equal:date_debut' : '')
        ]);

        $courses = $request->user()->courses;

        $active_course = $request->get('course_id');
        if ($active_course) {
            $active_course = Course::find($active_course);
            if (!$active_course || !in_array($active_course->id, $courses->pluck('id')->toArray())) {
                $active_course = null;
            }
        }

        $plannings = [];

        if ($active_course) {
            $plannings = $active_course->plannings->all();
        } else {
            foreach ($courses->pluck('plannings')->all() as $course_plannings) {
                $plannings = array_merge($plannings, $course_plannings->all());
            }
        }

        return view('etudiants.plannings', [
            'active_course' => $active_course,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'courses' => $courses,
            'plannings' => CourseRepository::planningsFilter($plannings, $date_debut, $date_fin)
        ]);
    }
}
