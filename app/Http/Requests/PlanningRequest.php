<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;

class PlanningRequest extends BaseRequest
{
    public function rules()
    {
        // si le temps est deja passe, le premier va se charger d'afficher l'erreur, et plus besoin de le faire pour les heure
        return [
            'cour_id' => 'nullable|exists:cours,id',
            'debut_date' => array('required', 'date', 'after:yesterday'),
            'debut_heure' => array('required', 'date_format:H:i', function ($attribute, $value, $fail) {
                $formDate = Carbon::createFromFormat('Y-m-d H:i', $this->debut_date . ' 23:59');
                if (!$formDate->isPast()) {
                    $formDate = Carbon::createFromFormat('Y-m-d H:i', $this->debut_date . ' ' . $value);
                    $verifDate = clone $formDate;
                    if ($verifDate->addMinutes(-30)->isPast()) {
                        $fail('Le temps est invalide et doit etre en avance d\'au moins 30 minutes');
                    }
                    $planning = Planning::whereRaw("cours_id = {$this->cours_id} AND date_debut <= '{$formDate->format('Y-m-d H:i')}' AND date_fin >= '{$formDate->format('Y-m-d H:i')}'")->first();
                    if ($planning) {
                        $isActualPlanning = $this->route('planning') ? $this->route('planning')->id == $planning->id : false;

                        if (!$isActualPlanning) {
                            $fail("La date et heure appartiennent a un intervalle deja planifie, commencant a {$planning->date_debut} et finissant a {$planning->date_fin} pour ce cours");
                        }
                    }
                }
            }),
            'fin_date' => array('nullable', 'date', 'after_or_equal:' . $this->debut_date),
            'fin_heure' => array('required', 'date_format:H:i', function ($attribute, $value, $fail) {
                $date = $this->fin_date ?? $this->debut_date;
                $debutDate = Carbon::createFromFormat('Y-m-d H:i', $this->debut_date . ' ' . $this->debut_heure);
                $formDate = Carbon::createFromFormat('Y-m-d H:i', $date . ' 23:59');
                if (!$formDate->isPast()) {
                    $formDate = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $value);
                    if ($formDate->getTimestamp() < $debutDate->getTimestamp()) {
                        $fail('La date et l\'heure de fin doivent etre superieur a la date de debut');
                    }
                    if (($formDate->getTimestamp() - $debutDate->getTimestamp()) < 1200) {
                        $fail('La date et l\'heure de fin doivent etre superieur a la date de debut d\'au moins 20 minutes');
                    }

                    $planning = Planning::whereRaw("cours_id = {$this->cours_id} AND date_debut < '{$formDate->format('Y-m-d H:i')}' AND date_fin >= '{$formDate->format('Y-m-d H:i')}'")->first();
                    if ($planning) {
                        $isActualPlanning = $this->route('planning') ? $this->route('planning')->id == $planning->id : false;

                        if (!$isActualPlanning) {
                            $fail("La date et l'heure appartiennent a un intervalle deja planifie, commencant a {$planning->date_debut} pour ce cours");
                        }
                    }
                }
            }),
        ];
    }
}
