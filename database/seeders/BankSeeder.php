<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Services\PaystackService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Bank::count() > 0) {
            return;
        }

        $paystackService = new PaystackService();
        $banks = $paystackService->getBanks();

        $banks = collect($banks)->map(function($bank) {
            return [
                'name' => $bank['name'],
                'code' => $bank['code'],
                'is_active' => $bank['active'],
            ];
        })->toArray();

        Bank::create([
            'name' => config('app.name'),
            'code' => '001122',
        ]);

        Bank::insert($banks);
    }
}
