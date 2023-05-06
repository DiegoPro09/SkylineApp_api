<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $level = new Level;
        $level->name = "SECUNDARIO";
        $level->save();

        $level2 = new Level;
        $level2->name = "PRIMARIO";
        $level2->save();

        $level3 = new Level;
        $level3->name = "SECUNDARIO";
        $level3->save();
    }
}
