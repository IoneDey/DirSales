<?php

namespace Database\Factories;

use App\Models\Timhd;
use App\Models\Timdt;
use App\Models\Barangs;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\timdt>
 */
class TimdtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $timhdIds = Timhd::pluck('id')->toArray();
        $barangsIds = Barangs::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Pastikan bahwa kita memiliki setidaknya satu ID yang tersedia untuk dipilih
        if (empty($timhdIds) || empty($barangsIds)) {
            throw new Exception('Tidak ada ID yang tersedia untuk dipilih.');
        }

        $nomerId = $this->faker->randomElement($timhdIds);
        $barangId = $this->faker->randomElement($barangsIds);

        // Cek keunikan kombinasi nomerid dan barangid
        $isUnique = false;
        $attemptCount = 0;

        while (!$isUnique) {
            // Cek apakah kombinasi nomerid dan barangid sudah ada dalam database
            $existingRecord = Timdt::where('nomerid', $nomerId)
                ->where('barangid', $barangId)
                ->exists();

            if (!$existingRecord) {
                $isUnique = true;
            } else {
                // Jika sudah ada, pilih nilai acak baru untuk nomerid dan barangid
                $nomerId = $this->faker->randomElement($timhdIds);
                $barangId = $this->faker->randomElement($barangsIds);

                $attemptCount++;

                // Batasi jumlah percobaan untuk menghindari loop tak terbatas
                if ($attemptCount > 100) {
                    throw new Exception('Gagal menemukan nilai unik untuk nomerid dan barangid.');
                }
            }
        }

        return [
            'nomerid' => $nomerId,
            'barangid' => $barangId,
            'hpp' => $this->faker->randomNumber(5, true),
            'hargajual' => $this->faker->randomNumber(6, true),
            'userid' => $this->faker->randomElement($userIds),
        ];
    }
}
