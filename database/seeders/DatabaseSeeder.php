<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\Behavior;
use \App\Models\CourseLevelDivision;
use \App\Models\Score;
use \App\Models\UserCourseAssignament;
use \App\Models\UserCourseLevel;
use \App\Models\Specialization;
use \App\Models\Genres;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleTableSeeder::class,
            LevelTableSeeder::class,
            PeriodTableSeeder::class,
            SpecializationTableSeeder::class,
            GenreTableSeeder::class,
            /*AssignamentTableSeeder::class,
            CourseTableSeeder::class,
            DivisionTableSeeder::class, */
        ]);
        /*
        User::factory(10)->create();
        Behavior::factory(15)->create();
        CourseLevelDivision::factory(21)->create();
        Score::factory(20)->create();
        UserCourseAssignament::factory(20)->create();
        UserCourseLevel::factory(20)->create();
        */
    }
}
