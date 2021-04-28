<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_debut', 'date_fin', 'cours_id'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime'
    ];

    protected $appends = [
        'debut_date', 'fin_date',
        'debut_heure', 'fin_heure'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }

    public function getDebutDateAttribute()
    {
        return $this->date_debut
            ? $this->date_debut->format('Y-m-d')
            : null;
    }

    public function getFinDateAttribute()
    {
        return $this->date_fin
            ? $this->date_fin->format('Y-m-d')
            : null;
    }

    public function getDebutHeureAttribute()
    {
        return $this->date_debut
            ? $this->date_debut->format('H:i')
            : null;
    }

    public function getFinHeureAttribute()
    {
        return $this->date_fin
            ? $this->date_fin->format('H:i')
            : null;
    }
}
