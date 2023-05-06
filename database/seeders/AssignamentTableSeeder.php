<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assignament;

class AssignamentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assignament = new Assignament;
        $assignament->name = "MATEMATICA";
        $assignament->save();

        $assignament2 = new Assignament;
        $assignament2->name = "LENGUA Y LITERATURA";
        $assignament2->save();

        $assignament3 = new Assignament;
        $assignament3->name = "PROGRAMACION";
        $assignament3->save();

        $assignament4 = new Assignament;
        $assignament4->name = "BASE DE DATOS";
        $assignament4->save();

        $assignament5 = new Assignament;
        $assignament5->name = "HISTORIA";
        $assignament5->save();
    }
}
