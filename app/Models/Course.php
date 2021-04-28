<?php

namespace App\Models;

use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'intitule',
        'user_id',
        'formation_id'
    ];

    protected $appends = [
        'stat_datas'
    ];

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function etudiants()
    {
        return $this->belongsToMany(User::class, 'cours_users', 'cours_id', 'user_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class, 'cours_id');
    }

    public function getStatDatasAttribute()
    {
        $plannings = $this->plannings;
        $data = [
            'passed' => 0,
            'coming' => 0,
        ];

        if (count($plannings)) {
            $date = Carbon::now()->format('Y-m-d H:i');
            $data['passed'] = CourseRepository::planningsFilter($plannings, null, $date, true)->count();
            $data['coming'] = CourseRepository::planningsFilter($plannings, $date, null, true)->count();
        }

        return $data;
    }
}
