<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pts;
use App\Models\User;
use App\Models\Kotas;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\timhd>
 */
class timhdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        static $nomer = 1;
        $Ptid = Pts::pluck('id')->random();
        $Kotaid = Kotas::pluck('id')->random();
        $UserId = User::pluck('id')->random();
        $tglawal = $this->faker->date();
        $tglawalCarbon = Carbon::createFromFormat('Y-m-d', $tglawal);
        $tglakhir = $tglawalCarbon->copy()->addMonth()->format('Y-m-d');

        return [
            'nomer' => 'TIM' . $nomer++,
            'ptid' => $Ptid,
            'kotaid' => $Kotaid,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'pic' => $this->faker->name($gender = 'female'),
            'userid' => $UserId,
        ];
    }
}
