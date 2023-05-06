<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialization = new Specialization;
        $specialization->name = "CICLO_BASICO";
        $specialization->save();

        $specialization2 = new Specialization;
        $specialization2->name = "TECNICO_EN_PROGRAMACION";
        $specialization2->save();

        $specialization3 = new Specialization;
        $specialization3->name = "BACHILLER_EN_EDUCACION_FISICA";
        $specialization3->save();
    }
}
