<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\GovtAccount;
use Database\Factories\GovtAccountFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovtAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GovtAccountFactory::new()->count(1000)->create();
    }
}
