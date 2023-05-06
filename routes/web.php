<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignamentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\CourseLevelDivisionController;
use App\Http\Controllers\CourseAssignamentController;
use App\Http\Controllers\UserCourseAssignamentController;
use App\Http\Controllers\UserCourseLevelController;
use App\Http\Controllers\BehaviorController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SpecializationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Carga de clases
use App\Http\Middleware\AuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

//Rutas de usuario
    Route::post('/api/register', [UserController::class, 'register']);
    Route::post('/api/login', [UserController::class, 'login']);

    //Rutas usuario general
        Route::get('/api/user', [UserController::class, 'index']);
        Route::get('/api/user/{id}', [UserController::class, 'show']);
        Route::put('/api/user/update', [UserController::class, 'update']);

    //Rutas usuario administrador
        Route::put('/api/user/update/{id}', [UserController::class, 'updateUser']);
        Route::post('/api/user/create', [UserController::class, 'createUser']);
        Route::delete('/api/user/delete/{id}', [UserController::class, 'destroy']);

//Rutas de Assignament
    Route::resource('/api/assignament', AssignamentController::class);

//Rutas de Courses
    Route::resource('/api/course', CourseController::class);

//Rutas de Divisions
    Route::resource('/api/division', DivisionController::class);

//Rutas de Scores
    Route::resource('/api/scores', ScoreController::class);

//Rutas de Behaviors
    Route::resource('/api/behavior', BehaviorController::class);

//Rutas de Behaviors
    Route::resource('/api/roles', RolesController::class);

//Rutas de Levels
    Route::resource('/api/level', LevelController::class);

//Rutas de Levels
    Route::resource('/api/specialization', SpecializationController::class);

//Rutas de relaciones
    Route::resource('/api/cld', CourseLevelDivisionController::class);
    Route::resource('/api/cac', CourseAssignamentController::class);
    Route::resource('/api/uca', UserCourseAssignamentController::class);
    Route::resource('/api/ucl', UserCourseLevelController::class);