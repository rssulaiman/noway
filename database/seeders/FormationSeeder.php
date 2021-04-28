<?php

namespace Database\Seeders;

use App\Models\Formation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Formation::count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                Formation::create([
                    'intitule' => 'Formation ' . $i,
                ]);
            }
        }

        // Formation::factory(5)->create();
    }
}
