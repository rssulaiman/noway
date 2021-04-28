<?php

namespace App\Http\Controllers;

use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function view($view_name, $data = [])
    {
        if (isset($data['courses'])) {
            $courses = $data['courses'];

            $courses_count = $courses->count();

            $plannings = [];

            foreach ($courses->pluck('plannings')->all() as $course_plannings) {
                $plannings = array_merge($plannings, $course_plannings->all());
            }

            $date = Carbon::now()->format('Y-m-d H:i');

            $passed_count = CourseRepository::planningsFilter($plannings, null, $date, true)->count();
            $programmed_count = CourseRepository::planningsFilter($plannings, $date, null, true)->count();

            $data = [
                'courses_count' => $courses_count,
                'passed_count' => $passed_count,
                'programmed_count' => $programmed_count
            ];
        }

        return view($view_name, $data);
    }
}
