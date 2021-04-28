<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        return $this->view('enseignants.index', [
            'courses' => $request->user()->courses
        ]);
    }

    public function show_courses(Request $request)
    {
        $courses = $request->user()->courses();

        if ($query_string = $request->get('query')) {
            $courses = $courses->where('intitule', 'LIKE', '%' . $query_string . '%');
        }

        return view('enseignants.courses', [
            'query' => $query_string,
            'courses' => $courses->paginate(20)
        ]);
    }


    public function show_course(Request $request, Course $course)
    {
        return view('enseignants.showCourse', [
            'course' => $course
        ]);
    }
}
