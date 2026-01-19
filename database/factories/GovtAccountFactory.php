<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GovtAccount>
 */
class GovtAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $allBanks = Bank::pluck('code')->where('code', '!=', '001122')->toArray();

        return [
            'account_number' => $this->faker->unique()->numerify('##########'),
            'bank_code' => $this->faker->randomElement($allBanks),
            'account_name' => $this->faker->name(),
            'balance' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'account_type' => $this->faker->randomElement(['savings', 'current']),
        ];
    }
}
