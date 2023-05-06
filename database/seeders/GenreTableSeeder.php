<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genres;

class GenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genre = new Genres;
        $genre->name = "MASCULINO";
        $genre->save();

        $genre2 = new Genres;
        $genre2->name = "FEMENINO";
        $genre2->save();
    }
}
