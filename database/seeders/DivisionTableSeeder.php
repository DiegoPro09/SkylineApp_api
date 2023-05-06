<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $division = new Division;
        $division->name = "A";
        $division->turn = "MAÑANA";
        $division->save();

        $division2 = new Division;
        $division2->name = "A";
        $division2->turn = "MAÑANA";
        $division2->save();

        $division3 = new Division;
        $division3->name = "A";
        $division3->turn = "MAÑANA";
        $division3->save();

        $division4 = new Division;
        $division4->name = "A";
        $division4->turn = "TARDE";
        $division4->save();

        $division5 = new Division;
        $division5->name = "A";
        $division5->turn = "TARDE";
        $division5->save();
    }
}
