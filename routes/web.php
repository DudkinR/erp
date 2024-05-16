<?php

use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', 'App\Http\Controllers\Auth\AuthenticatedSessionController@create')->name('login');
    Route::post('/login', 'App\Http\Controllers\Auth\AuthenticatedSessionController@store');
    Route::get('/register', 'App\Http\Controllers\Auth\RegisteredUserController@create')->name('register');
    Route::post('/register', 'App\Http\Controllers\Auth\RegisteredUserController@store');
    Route::get('/forgot-password', 'App\Http\Controllers\Auth\PasswordResetLinkController@create')->name('password.request');
    Route::post('/forgot-password', 'App\Http\Controllers\Auth\PasswordResetLinkController@store')->name('password.email');
    Route::get('/reset-password/{token}', 'App\Http\Controllers\Auth\NewPasswordController@create')->name('password.reset');
    Route::post('/reset-password', 'App\Http\Controllers\Auth\NewPasswordController@store')->name('password.store');
});

Route::middleware('auth')->group(function () {
    // goals routes
    Route::get('/goals', 'App\Http\Controllers\GoalController@index')->name('goals.index');
    Route::get('/goals/create', 'App\Http\Controllers\GoalController@create')->name('goals.create');
    Route::post('/goals', 'App\Http\Controllers\GoalController@store')->name('goals.store');
    Route::get('/goals/{id}', 'App\Http\Controllers\GoalController@show')->name('goals.show');
    Route::get('/goals/{id}/edit', 'App\Http\Controllers\GoalController@edit')->name('goals.edit');
    Route::put('/goals/{id}', 'App\Http\Controllers\GoalController@update')->name('goals.update');
    Route::delete('/goals/{id}', 'App\Http\Controllers\GoalController@destroy')->name('goals.destroy');

    // funs routes
    Route::get('/funs', 'App\Http\Controllers\FunController@index')->name('funs.index');
    Route::get('/funs/create', 'App\Http\Controllers\FunController@create')->name('funs.create');
    Route::post('/funs', 'App\Http\Controllers\FunController@store')->name('funs.store');
    Route::get('/funs/{id}', 'App\Http\Controllers\FunController@show')->name('funs.show');
    Route::get('/funs/{id}/edit', 'App\Http\Controllers\FunController@edit')->name('funs.edit');
    Route::put('/funs/{id}', 'App\Http\Controllers\FunController@update')->name('funs.update');
    Route::delete('/funs/{id}', 'App\Http\Controllers\FunController@destroy')->name('funs.destroy');

    // personal routes
    Route::get('/personal', 'App\Http\Controllers\PersonalController@index')->name('personal.index');
    Route::get('/personal/create', 'App\Http\Controllers\PersonalController@create')->name('personal.create');
    Route::post('/personal', 'App\Http\Controllers\PersonalController@store')->name('personal.store');
    Route::get('/personal/{id}', 'App\Http\Controllers\PersonalController@show')->name('personal.show');
    Route::get('/personal/{id}/edit', 'App\Http\Controllers\PersonalController@edit')->name('personal.edit');
    Route::put('/personal/{id}', 'App\Http\Controllers\PersonalController@update')->name('personal.update');
    Route::delete('/personal/{id}', 'App\Http\Controllers\PersonalController@destroy')->name('personal.destroy');
    // import personal data from csv file
    Route::get('/personalimport', 'App\Http\Controllers\PersonalController@import')->name('personal.import');
    Route::post('/personalimport', 'App\Http\Controllers\PersonalController@importData')->name('personal.importData');

    // structure routes
    Route::get('/structure', 'App\Http\Controllers\StructureController@index')->name('structure.index');
    Route::get('/structure/create', 'App\Http\Controllers\StructureController@create')->name('structure.create');
    Route::post('/structure', 'App\Http\Controllers\StructureController@store')->name('structure.store');
    Route::get('/structure/{id}', 'App\Http\Controllers\StructureController@show')->name('structure.show');
    Route::get('/structure/{id}/edit', 'App\Http\Controllers\StructureController@edit')->name('structure.edit');
    Route::put('/structure/{id}', 'App\Http\Controllers\StructureController@update')->name('structure.update');
    Route::delete('/structure/{id}', 'App\Http\Controllers\StructureController@destroy')->name('structure.destroy');
    //  structure select csv file for import
    Route::get('/structureimport', 'App\Http\Controllers\StructureController@import')->name('structure.import');
    Route::post('/structureimport', 'App\Http\Controllers\StructureController@importData')->name('structure.importData');


    // criteria routes
    Route::get('/criteria', 'App\Http\Controllers\CriteriaController@index')->name('criteria.index');
    Route::get('/criteria/create', 'App\Http\Controllers\CriteriaController@create')->name('criteria.create');
    Route::post('/criteria', 'App\Http\Controllers\CriteriaController@store')->name('criteria.store');
    Route::get('/criteria/{id}', 'App\Http\Controllers\CriteriaController@show')->name('criteria.show');
    Route::get('/criteria/{id}/edit', 'App\Http\Controllers\CriteriaController@edit')->name('criteria.edit');
    Route::put('/criteria/{id}', 'App\Http\Controllers\CriteriaController@update')->name('criteria.update');
    Route::delete('/criteria/{id}', 'App\Http\Controllers\CriteriaController@destroy')->name('criteria.destroy');

    // facts routes
    Route::get('/facts', 'App\Http\Controllers\FactsController@index')->name('facts.index');
    Route::get('/facts/create', 'App\Http\Controllers\FactsController@create')->name('facts.create');
    Route::post('/facts', 'App\Http\Controllers\FactsController@store')->name('facts.store');
    Route::get('/facts/{id}', 'App\Http\Controllers\FactsController@show')->name('facts.show');
    Route::get('/facts/{id}/edit', 'App\Http\Controllers\FactsController@edit')->name('facts.edit');
    Route::put('/facts/{id}', 'App\Http\Controllers\FactsController@update')->name('facts.update');
    Route::delete('/facts/{id}', 'App\Http\Controllers\FactsController@destroy')->name('facts.destroy');

    // categories routes
    Route::get('/categories', 'App\Http\Controllers\CategoryController@index')->name('cats.index');
    Route::get('/categories/create', 'App\Http\Controllers\CategoryController@create')->name('cats.create');
    Route::post('/categories', 'App\Http\Controllers\CategoryController@store')->name('cats.store');
    Route::get('/categories/{id}', 'App\Http\Controllers\CategoryController@show')->name('cats.show');
    Route::get('/categories/{id}/edit', 'App\Http\Controllers\CategoryController@edit')->name('cats.edit');
    Route::put('/categories/{id}', 'App\Http\Controllers\CategoryController@update')->name('cats.update');
    Route::delete('/categories/{id}', 'App\Http\Controllers\CategoryController@destroy')->name('cats.destroy');

    // doc routes
    Route::get('/docs', 'App\Http\Controllers\DocController@index')->name('docs.index');
    Route::get('/docs/create', 'App\Http\Controllers\DocController@create')->name('docs.create');
    Route::post('/docs', 'App\Http\Controllers\DocController@store')->name('docs.store');
    Route::any('/apistoredocs', 'App\Http\Controllers\DocController@apistoredocs')->name('docs.apistoredocs');
    Route::get('/docs/{id}', 'App\Http\Controllers\DocController@show')->name('docs.show');
    Route::get('/docs/{id}/edit', 'App\Http\Controllers\DocController@edit')->name('docs.edit');
    Route::put('/docs/{id}', 'App\Http\Controllers\DocController@update')->name('docs.update');
    Route::delete('/docs/{id}', 'App\Http\Controllers\DocController@destroy')->name('docs.destroy');
    // import data 
    Route::get('/docsimport', 'App\Http\Controllers\DocController@import')->name('docs.import');
    Route::post('/docsimport', 'App\Http\Controllers\DocController@importData')->name('docs.importData');

    //all imports index 
    Route::get('/imports', 'App\Http\Controllers\CommonController@index')->name('imports.index');

    //type routes
    Route::get('/types', 'App\Http\Controllers\TypeController@index')->name('types.index');
    Route::get('/types/create', 'App\Http\Controllers\TypeController@create')->name('types.create');
    Route::post('/types', 'App\Http\Controllers\TypeController@store')->name('types.store');
    Route::get('/types/{id}', 'App\Http\Controllers\TypeController@show')->name('types.show');
    Route::get('/types/{id}/edit', 'App\Http\Controllers\TypeController@edit')->name('types.edit');
    Route::put('/types/{id}', 'App\Http\Controllers\TypeController@update')->name('types.update');
    Route::delete('/types/{id}', 'App\Http\Controllers\TypeController@destroy')->name('types.destroy');
    // import data from csv file
    Route::get('/typesimport', 'App\Http\Controllers\TypeController@import')->name('types.import');
    Route::post('/typesimport', 'App\Http\Controllers\TypeController@importData')->name('types.importData');

    // project routes
    Route::get('/projects', 'App\Http\Controllers\ProjectController@index')->name('projects.index');
    Route::get('/projects/create', 'App\Http\Controllers\ProjectController@create')->name('projects.create');
    Route::post('/projects', 'App\Http\Controllers\ProjectController@store')->name('projects.store');
    Route::get('/projects/{id}', 'App\Http\Controllers\ProjectController@show')->name('projects.show');
    Route::get('/projects/{id}/edit', 'App\Http\Controllers\ProjectController@edit')->name('projects.edit');
    Route::put('/projects/{id}', 'App\Http\Controllers\ProjectController@update')->name('projects.update');
    Route::delete('/projects/{id}', 'App\Http\Controllers\ProjectController@destroy')->name('projects.destroy');
    // post projects.add_stage
    Route::post('/projectstage/add_stage', 'App\Http\Controllers\ProjectController@add_stage')->name('projects.add_stage');
    // formprojectts.add_stage 
    Route::any('/formprojectts/add_stage', 'App\Http\Controllers\ProjectController@add_stage_form')->name('projects.add_stage_form');
    // /projectstgantt
    Route::get('/projectstgantt/{id}', 'App\Http\Controllers\ProjectController@projectstgantt')->name('projects.projectstgantt');

    // import data from csv file
    Route::get('/projectsimport', 'App\Http\Controllers\ProjectController@import')->name('projects.import');
    Route::post('/projectsimport', 'App\Http\Controllers\ProjectController@importData')->name('projects.importData');
    //testproject
    Route::any('/testproject', 'App\Http\Controllers\ProjectController@testproject')->name('projects.testproject');

    // control routes
    Route::get('/controls', 'App\Http\Controllers\ControlController@index')->name('controls.index');
    Route::get('/controls/create', 'App\Http\Controllers\ControlController@create')->name('controls.create');   
    Route::post('/controls', 'App\Http\Controllers\ControlController@store')->name('controls.store');
    Route::get('/controls/{id}', 'App\Http\Controllers\ControlController@show')->name('controls.show');
    Route::get('/controls/{id}/edit', 'App\Http\Controllers\ControlController@edit')->name('controls.edit');
    Route::put('/controls/{id}', 'App\Http\Controllers\ControlController@update')->name('controls.update');
    Route::delete('/controls/{id}', 'App\Http\Controllers\ControlController@destroy')->name('controls.destroy');

    // step routes
    Route::get('/steps', 'App\Http\Controllers\StepController@index')->name('steps.index');
    Route::get('/steps/create', 'App\Http\Controllers\StepController@create')->name('steps.create');
    Route::any('/steps', 'App\Http\Controllers\StepController@store')->name('steps.store');
    Route::get('/steps/{id}', 'App\Http\Controllers\StepController@show')->name('steps.show');
    Route::get('/steps/{id}/edit', 'App\Http\Controllers\StepController@edit')->name('steps.edit');
    Route::put('/steps/{id}', 'App\Http\Controllers\StepController@update')->name('steps.update');
    Route::delete('/steps/{id}', 'App\Http\Controllers\StepController@destroy')->name('steps.destroy');

    // stage routes
    Route::get('/stages', 'App\Http\Controllers\StageController@index')->name('stages.index');
    Route::get('/stages/create', 'App\Http\Controllers\StageController@create')->name('stages.create');
    Route::post('/stages', 'App\Http\Controllers\StageController@store')->name('stages.store');
    Route::get('/stages/{id}', 'App\Http\Controllers\StageController@show')->name('stages.show');
    Route::get('/stages/{id}/edit', 'App\Http\Controllers\StageController@edit')->name('stages.edit');
    Route::put('/stages/{id}', 'App\Http\Controllers\StageController@update')->name('stages.update');
    Route::delete('/stages/{id}', 'App\Http\Controllers\StageController@destroy')->name('stages.destroy');
    //stages.add_step
    Route::post('/stagestep/add_step', 'App\Http\Controllers\StageController@add_step')->name('stages.add_step');
    // /stagesstep/remove_step
    Route::any('stagesstep/remove_step', 'App\Http\Controllers\StageController@remove_step')->name('stages.remove_step');

    // dimension routes
    Route::get('/dimensions', 'App\Http\Controllers\DimensioneController@index')->name('dimensions.index');
    Route::get('/dimensions/create', 'App\Http\Controllers\DimensioneController@create')->name('dimensions.create');
    Route::post('/dimensions', 'App\Http\Controllers\DimensioneController@store')->name('dimensions.store');
    Route::get('/dimensions/{id}', 'App\Http\Controllers\DimensioneController@show')->name('dimensions.show');
    Route::get('/dimensions/{id}/edit', 'App\Http\Controllers\DimensioneController@edit')->name('dimensions.edit');
    Route::put('/dimensions/{id}', 'App\Http\Controllers\DimensioneController@update')->name('dimensions.update');
    Route::delete('/dimensions/{id}', 'App\Http\Controllers\DimensioneController@destroy')->name('dimensions.destroy');
    // import data from csv file
    Route::get('/dimensionsimport', 'App\Http\Controllers\DimensioneController@import')->name('dimensions.import');
    Route::post('/dimensionsimport', 'App\Http\Controllers\DimensioneController@importData')->name('dimensions.importData');

    //nomenclaturs
    Route::get('/nomenclaturs', 'App\Http\Controllers\NomenclatureController@index')->name('nomenclaturs.index');
    Route::get('/nomenclaturs/create', 'App\Http\Controllers\NomenclatureController@create')->name('nomenclaturs.create');
    Route::post('/nomenclaturs', 'App\Http\Controllers\NomenclatureController@store')->name('nomenclaturs.store');
    Route::get('/nomenclaturs/{id}', 'App\Http\Controllers\NomenclatureController@show')->name('nomenclaturs.show');
    Route::get('/nomenclaturs/{id}/edit', 'App\Http\Controllers\NomenclatureController@edit')->name('nomenclaturs.edit');
    Route::put('/nomenclaturs/{id}', 'App\Http\Controllers\NomenclatureController@update')->name('nomenclaturs.update');
    Route::delete('/nomenclaturs/{id}', 'App\Http\Controllers\NomenclatureController@destroy')->name('nomenclaturs.destroy');
    // import data from csv file
    Route::get('/nomenclaturesimport', 'App\Http\Controllers\NomenclatureController@import')->name('nomenclaturs.import');
    Route::post('/nomenclaturesimport', 'App\Http\Controllers\NomenclatureController@importData')->name('nomenclaturs.importData');
    //nomenclatures.docs.create
    Route::get('/nomenclatures/{id}/docs/create', 'App\Http\Controllers\NomenclatureController@createDoc')->name('nomenclatures.docs.create');
    //nomenclatures.docs.store
    Route::post('/nomenclatures/docs/store', 'App\Http\Controllers\NomenclatureController@storeDoc')->name('nomenclatures.docs.store');
    //nomenclatures.img.create
    Route::get('/nomenclatures/{id}/img/create', 'App\Http\Controllers\NomenclatureController@createImg')->name('nomenclatures.img.create');
    //nomenclatures.img.store
    Route::post('/nomenclatures/img/store', 'App\Http\Controllers\NomenclatureController@storeImg')->name('nomenclatures.img.store');
    
    
    // problems routes
    Route::get('/problems', 'App\Http\Controllers\ProblemController@index')->name('problems.index');
    Route::get('/problems/create', 'App\Http\Controllers\ProblemController@create')->name('problems.create');
    Route::post('/problems', 'App\Http\Controllers\ProblemController@store')->name('problems.store');
    Route::get('/problems/{id}', 'App\Http\Controllers\ProblemController@show')->name('problems.show');
    Route::get('/problems/{id}/edit', 'App\Http\Controllers\ProblemController@edit')->name('problems.edit');
    Route::put('/problems/{id}', 'App\Http\Controllers\ProblemController@update')->name('problems.update');
    Route::delete('/problems/{id}', 'App\Http\Controllers\ProblemController@destroy')->name('problems.destroy');
    // tasks routes
    Route::get('/tasks', 'App\Http\Controllers\TaskController@index')->name('tasks.index');
    Route::get('/tasks/create', 'App\Http\Controllers\TaskController@create')->name('tasks.create');
    Route::post('/tasks', 'App\Http\Controllers\TaskController@store')->name('tasks.store');
    Route::get('/tasks/{id}', 'App\Http\Controllers\TaskController@show')->name('tasks.show');
    Route::get('/tasks/{id}/edit', 'App\Http\Controllers\TaskController@edit')->name('tasks.edit');
    Route::put('/tasks/{id}', 'App\Http\Controllers\TaskController@update')->name('tasks.update');
    Route::delete('/tasks/{id}', 'App\Http\Controllers\TaskController@destroy')->name('tasks.destroy');
    // add new stages
    Route::post('/addNewStages', 'App\Http\Controllers\TaskController@addNewStages')->name('tasks.addNewStages');
    // positions routes
    Route::get('/positions', 'App\Http\Controllers\PositionController@index')->name('positions.index');
    Route::get('/positions/create', 'App\Http\Controllers\PositionController@create')->name('positions.create');
    Route::post('/positions', 'App\Http\Controllers\PositionController@store')->name('positions.store');
    Route::get('/positions/{id}', 'App\Http\Controllers\PositionController@show')->name('positions.show');
    Route::get('/positions/{id}/edit', 'App\Http\Controllers\PositionController@edit')->name('positions.edit');
    Route::put('/positions/{id}', 'App\Http\Controllers\PositionController@update')->name('positions.update');
    Route::delete('/positions/{id}', 'App\Http\Controllers\PositionController@destroy')->name('positions.destroy');

    //profile routes
     Route::get('/profile/{id}/edit', 'App\Http\Controllers\ProfileController@edit')->name('profiles.edit');
    Route::get('/profile', 'App\Http\Controllers\ProfileController@index')->name('profiles.index');
   Route::get('/profile/{id}', 'App\Http\Controllers\ProfileController@show')->name('profiles.show');
     Route::put('/profile/{id}', 'App\Http\Controllers\ProfileController@update')->name('profiles.update');
    Route::delete('/profile/{id}', 'App\Http\Controllers\ProfileController@destroy')->name('profiles.destroy');
    // import data 
    Route::get('/profileimport', 'App\Http\Controllers\ProfileController@import')->name('profiles.import');

    // тестовый роут
    Route::get('/test', 'App\Http\Controllers\DocController@test')->name('test');
});







