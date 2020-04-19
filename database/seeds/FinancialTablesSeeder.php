<?php

use Illuminate\Database\Seeder;
use App\Models\Financial\Promocode;
use App\Models\Financial\Plan;

class FinancialTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Promocode::class, 10)->create();

        factory(Plan::class, 10)->create();
    }
}
