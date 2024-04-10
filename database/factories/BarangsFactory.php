<?php

namespace Database\Factories;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barangs>
 */
class BarangsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $UserId = Users::pluck('id')->random();

        return [
            'kode' => $this->faker->unique()->bothify('BRG-#####??????'),
            'nama' => $this->faker->unique()->words(mt_rand(3, 5), true),
            'userid' => $UserId
        ];
    }
}
