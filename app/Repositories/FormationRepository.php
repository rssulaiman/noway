<?php

namespace App\Repositories;

use App\Models\Formation;

class FormationRepository
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return Formation::query();
    }

    /**
     * @param int|null $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFormations(int $user_id = null)
    {
        $formations = $this->newQuery();

        if ($user_id) {
            $formations = $formations->where('user_id', $user_id);
        }

        return $formations;
    }
}
