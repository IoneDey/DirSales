<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pt>
 */
class PtsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $UserId = Users::pluck('id')->random();

        $word = $this->faker->unique()->words(1, true);
        if (strlen($word) > 10) {
            $word = substr($word, 0, 10); // Ambil hanya 10 karakter pertama
        }
        return [
            'kode' => $word,
            'nama' => $this->faker->words(1, true, 255),
            'angsuranhari' => $this->faker->numberBetween(7, 10),
            'angsuranperiode' => $this->faker->numberBetween(4, 7),
            'userid' => $UserId,
        ];
    }
}
