<?php

namespace App\Repositories;

use App\Models\Cours;
use App\Models\Course;
use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;

class CourseRepository
{
    public function newQuery()
    {
        return Course::query();
    }

    public function getCourses($user_id = null)
    {
        $courses = $this->newQuery();
        if ($user_id) {
            $courses = $courses->where('user_id', $user_id);
        }

        return $courses;
    }

    public function getFormationCourses($formation_id = null)
    {
        $courses = $this->newQuery();

        if ($formation_id) {
            $courses = $courses->where('formation_id', $formation_id);
        }

        return $courses;
    }

    public function getOthersFormationCourses($formation_id = null)
    {
        $courses = $this->newQuery();

        if ($formation_id) {
            $courses = $courses->where('formation_id', $formation_id);
        }

        if ($user = request()->user()) {
            $courses = $courses->leftJoin('cours_users', 'cours.id', '=', 'cours_users.cours_id')
                ->where('cours_users.user_id', '=', null);
        }

        return $courses;
    }

    /**
     * Juste accessible a l'administrateur
     */
    public function getCoursesPlannings(
        $course = null,
        $dateDebut = null,
        $dateFin = null,
        $orderColumn = 'date_debut',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        if (!request()->user()->is_admin) {
            abort(500);
        }

        if ($course) {
            if (!($course instanceof Course)) {
                throw new \Exception('Invalid course');
            }

            $plannings = $course->plannings->all();
        } else {
            $plannings = Planning::all();
        }

        return self::planningsFilter($plannings, $dateDebut, $dateFin, false, $orderColumn, $order, $dateFormat);
    }

    public function getUserCoursesPlannings(
        $course = null,
        $dateDebut = null,
        $dateFin = null,
        $user = null,
        $orderColumn = 'date_debut',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        $user = $user ?? request()->user();

        if (!$user || $user->is_admin) {
            abort(500);
        }

        if ($user->is_enseignant || $user->is_etudiant) {
            $plannings = [];

            if ($course) {
                if (!($course instanceof Course)) {
                    throw new \Exception('Invalid course');
                }

                $plannings = $course->plannings->all();
            } else {
                foreach ($user->courses->pluck('plannings')->all() as $course_plannings) {
                    $plannings = array_merge($plannings, $course_plannings->all());
                }
            }

            return self::planningsFilter($plannings, $dateDebut, $dateFin, false, $orderColumn, $order, $dateFormat);
        }

        return null;
    }

    public static function planningsFilter(
        $plannings,
        $dateDebut = null,
        $dateFin = null,
        $strict = false,
        $orderColumn = 'date_debut',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'DESC';
        $orderColumn = in_array($orderColumn, ['date_debut', 'date_fin']) ? $orderColumn : 'date_debut';

        $plannings = collect($plannings);

        if ($dateDebut) {
            $date = $strict ? $dateDebut : $dateDebut . ' 00:00';
            $dateDebut = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        if ($dateFin) {
            $date = $strict ? $dateFin : $dateFin . ' 23:59';
            $dateFin = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        $plannings = $plannings->filter(function ($value, $key) use ($dateDebut, $dateFin) {
            $result = true;
            $timestamp = $value->date_debut->getTimestamp();

            if ($dateDebut) {
                $result = $timestamp >= $dateDebut;
            }

            if ($result && $dateFin) {
                $result = $timestamp <= $dateFin;
            }

            return $result;
        });

        return $order == 'DESC'
            ? $plannings->sortByDesc($orderColumn)
            : $plannings->sortBy($orderColumn);
    }
}
