<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public function getAuthPassword()
    {
        return $this->mdp;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'login',
        'mdp',
        'type',
        'formation_id',
    ];

    protected $appends = [
        'is_admin',
        'is_enseignant',
        'is_etudiant',
        'full_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function isValid()
    {
        return $this->type !== null;
    }

    public function getIsAdminAttribute()
    {
        return $this->type === 'admin';
    }

    public function getIsEnseignantAttribute()
    {
        return $this->type === 'enseignant';
    }

    public function getIsEtudiantAttribute()
    {
        return $this->type === 'etudiant';
    }

    public function courses()
    {
        return $this->is_etudiant
            ? $this->belongsToMany(Course::class, 'cours_users', 'user_id', 'cours_id')
            : $this->hasMany(Course::class, 'user_id');
    }

    /**
     * Les cours auxquels l'étudiant est inscrit
     *
     * @return mixed
     */

    // public function liste() {
    //     return $this->belongsToMany(Cours::class, '', 'user_id', 'cours_id');
    //       ->withPivot('')
    //       ->withTimestamps();
    // }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
