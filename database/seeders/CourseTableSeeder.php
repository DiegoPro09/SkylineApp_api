<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course = new Course;
        $course->number = 1;
        $course->name = "AÑO";
        $course->save();

        $course2 = new Course;
        $course2->number = 2;
        $course2->name = "AÑO";
        $course2->save();

        $course3 = new Course;
        $course3->number = 3;
        $course3->name = "AÑO";
        $course3->save();

        $course4 = new Course;
        $course4->number = 4;
        $course4->name = "AÑO";
        $course4->save();

        $course5 = new Course;
        $course5->number = 5;
        $course5->name = "AÑO";
        $course5->save();
    }
}
