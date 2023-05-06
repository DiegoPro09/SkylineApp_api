<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $period = new Period;
        $period->name = "PRIMER_SEMESTRE";
        $period->state = 1;
        $period->save();

        $period2 = new Period;
        $period2->name = "SEGUNDO_SEMESTRE";
        $period2->state = 1;
        $period2->save();

        $period3 = new Period;
        $period3->name = "FINAL";
        $period3->state = 1;
        $period3->save();
    }
}
