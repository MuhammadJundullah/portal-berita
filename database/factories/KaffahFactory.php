<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\kaffah;

class KaffahFactory extends Factory
{
    protected $model = kaffah::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'tahun' => $this->faker->year,
            'januari' => '✔',
            'februari' => '✘',
            'maret' => '✔',
            'april' => '✔',
            'mei' => '✔',
            'juni' => '✔',
            'juli' => '✔',
            'agustus' => '✔',
            'september' => '✔',
            'oktober' => '✔',
            'november' => '✔',
            'desember' => '✔',
        ];
    }
}
