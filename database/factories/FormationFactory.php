<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FormationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Formation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'intitule' => $this->faker->company,
        ];
    }
}
