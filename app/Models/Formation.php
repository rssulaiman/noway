<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'intitule'
    ];

    protected $with = [
        'users'
    ];

    protected $appends = [
        'etudiants'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getEtudiantsAttribute()
    {
        return $this->users()->where('type', 'etudiant')->get();
    }
}
