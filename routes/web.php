<?php

use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('home');
// save  var sessions
Route::get('/ss', 'App\Http\Controllers\ApiController@saveSession')->name('save-session');

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

// dictionary routes
Route::get('/dictionary', 'App\Http\Controllers\DictionaryController@index')->name('dictionary.index');
Route::get('/dictionary/create', 'App\Http\Controllers\DictionaryController@create')->name('dictionary.create');
Route::post('/dictionary', 'App\Http\Controllers\DictionaryController@store')->name('dictionary.store');
Route::get('/dictionary/{id}', 'App\Http\Controllers\DictionaryController@show')->name('dictionary.show');
Route::get('/dictionaryedit', 'App\Http\Controllers\DictionaryController@edit')->name('dictionary.edit');
Route::put('/dictionary/{id}', 'App\Http\Controllers\DictionaryController@update')->name('dictionary.update');
Route::delete('/dictionary/{id}', 'App\Http\Controllers\DictionaryController@destroy')->name('dictionary.destroy');


Route::middleware('auth')->group(function () {
// dictionary import routes
    Route::get('/dictionaryimport', 'App\Http\Controllers\DictionaryController@import')->name('dictionary.import');
    Route::post('/dictionaryimport', 'App\Http\Controllers\DictionaryController@importData')->name('dictionary.importData');
  // goals routes
  Route::get('/goals', 'App\Http\Controllers\GoalController@index')->name('goals.index');
  Route::get('/goals/create', 'App\Http\Controllers\GoalController@create')->name('goals.create');
  Route::post('/goals', 'App\Http\Controllers\GoalController@store')->name('goals.store');
  Route::get('/goals/{id}', 'App\Http\Controllers\GoalController@show')->name('goals.show');
  Route::get('/goals/{id}/edit', 'App\Http\Controllers\GoalController@edit')->name('goals.edit');
  Route::put('/goals/{id}', 'App\Http\Controllers\GoalController@update')->name('goals.update');
  Route::delete('/goals/{id}', 'App\Http\Controllers\GoalController@destroy')->name('goals.destroy');

    // briefs routes
    Route::get('/briefs', 'App\Http\Controllers\BriefController@index')->name('briefs.index');
    Route::get('/briefs/create', 'App\Http\Controllers\BriefController@create')->name('briefs.create');
    Route::post('/briefs', 'App\Http\Controllers\BriefController@store')->name('briefs.store');
    Route::get('/briefs/{id}', 'App\Http\Controllers\BriefController@show')->name('briefs.show');
    Route::get('/briefs/{id}/edit', 'App\Http\Controllers\BriefController@edit')->name('briefs.edit');
    Route::put('/briefs/{id}', 'App\Http\Controllers\BriefController@update')->name('briefs.update');
    Route::delete('/briefs/{id}', 'App\Http\Controllers\BriefController@destroy')->name('briefs.destroy');
  // systems routes
  Route::get('/systems', 'App\Http\Controllers\SystemController@index')->name('systems.index');
  Route::get('/systems/create', 'App\Http\Controllers\SystemController@create')->name('systems.create');
  Route::post('/systems', 'App\Http\Controllers\SystemController@store')->name('systems.store');
  Route::get('/systems/{id}', 'App\Http\Controllers\SystemController@show')->name('systems.show');
  Route::get('/systems/{id}/edit', 'App\Http\Controllers\SystemController@edit')->name('systems.edit');
  Route::put('/systems/{id}', 'App\Http\Controllers\SystemController@update')->name('systems.update');
  Route::delete('/systems/{id}', 'App\Http\Controllers\SystemController@destroy')->name('systems.destroy');

    // jits routes
Route::get('/jits', 'App\Http\Controllers\JitController@index')->name('jits.index');
Route::get('/jits/create', 'App\Http\Controllers\JitController@create')->name('jits.create');
Route::post('/jits', 'App\Http\Controllers\JitController@store')->name('jits.store');
Route::get('/jits/{id}', 'App\Http\Controllers\JitController@show')->name('jits.show');
Route::get('/jits/{id}/edit', 'App\Http\Controllers\JitController@edit')->name('jits.edit');
Route::put('/jits/{id}', 'App\Http\Controllers\JitController@update')->name('jits.update');
Route::delete('/jits/{id}', 'App\Http\Controllers\JitController@destroy')->name('jits.destroy');
// jitqws routes
Route::get('/jitqws', 'App\Http\Controllers\JitqwController@index')->name('jitqws.index');
Route::get('/jitqws/create', 'App\Http\Controllers\JitqwController@create')->name('jitqws.create');
Route::post('/jitqws', 'App\Http\Controllers\JitqwController@store')->name('jitqws.store');
Route::get('/jitqws/{id}', 'App\Http\Controllers\JitqwController@show')->name('jitqws.show');
Route::get('/jitqws/{id}/edit', 'App\Http\Controllers\JitqwController@edit')->name('jitqws.edit');
Route::put('/jitqws/{id}', 'App\Http\Controllers\JitqwController@update')->name('jitqws.update');
Route::delete('/jitqws/{id}', 'App\Http\Controllers\JitqwController@destroy')->name('jitqws.destroy');


    // objectives routes
    Route::get('/objectives', 'App\Http\Controllers\ObjectiveController@index')->name('objectives.index');
    Route::get('/objectives/create', 'App\Http\Controllers\ObjectiveController@create')->name('objectives.create');
    Route::post('/objectives', 'App\Http\Controllers\ObjectiveController@store')->name('objectives.store');
    Route::get('/objectives/{id}', 'App\Http\Controllers\ObjectiveController@show')->name('objectives.show');
    Route::get('/objectives/{id}/edit', 'App\Http\Controllers\ObjectiveController@edit')->name('objectives.edit');
    Route::put('/objectives/{id}', 'App\Http\Controllers\ObjectiveController@update')->name('objectives.update');
    Route::delete('/objectives/{id}', 'App\Http\Controllers\ObjectiveController@destroy')->name('objectives.destroy');


    // funs routes
    Route::get('/funs', 'App\Http\Controllers\FunController@index')->name('funs.index');
    Route::get('/funs/create', 'App\Http\Controllers\FunController@create')->name('funs.create');
    Route::post('/funs', 'App\Http\Controllers\FunController@store')->name('funs.store');
    Route::get('/funs/{id}', 'App\Http\Controllers\FunController@show')->name('funs.show');
    Route::get('/funs/{id}/edit', 'App\Http\Controllers\FunController@edit')->name('funs.edit');
    Route::put('/funs/{id}', 'App\Http\Controllers\FunController@update')->name('funs.update');
    Route::delete('/funs/{id}', 'App\Http\Controllers\FunController@destroy')->name('funs.destroy');
    // store_api
    Route::any('/fun/store_api', 'App\Http\Controllers\FunController@store_api')->name('funs.store_api');
//funs.store_positions_api
    Route::any('/funs.store_positions_api', 'App\Http\Controllers\FunController@store_positions_api')->name('funs.store_positions_api');
    // personal routes
    
    Route::get('/personal', 'App\Http\Controllers\PersonalController@index')->name('personal.index');
    Route::get('/personal/create', 'App\Http\Controllers\PersonalController@create')->name('personal.create');
    Route::post('/personal', 'App\Http\Controllers\PersonalController@store')->name('personal.store');
    Route::get('/personal/{id}', 'App\Http\Controllers\PersonalController@show')->name('personal.show');
    Route::get('/personal/{id}/edit', 'App\Http\Controllers\PersonalController@edit')->name('personal.edit');
    Route::put('/personal/{id}', 'App\Http\Controllers\PersonalController@update')->name('personal.update');
    Route::delete('/personal/{id}', 'App\Http\Controllers\PersonalController@destroy')->name('personal.destroy');
    // search personal
    Route::any('/search-personal', 'App\Http\Controllers\PersonalController@search')->name('personal.search');
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

    // divisions routes
    Route::get('/divisions', 'App\Http\Controllers\DivisionController@index')->name('divisions.index');
    Route::get('/divisions/create', 'App\Http\Controllers\DivisionController@create')->name('divisions.create');
    Route::post('/divisions', 'App\Http\Controllers\DivisionController@store')->name('divisions.store');
    Route::get('/divisions/{id}', 'App\Http\Controllers\DivisionController@show')->name('divisions.show');
    Route::get('/divisions/{id}/edit', 'App\Http\Controllers\DivisionController@edit')->name('divisions.edit');
    Route::put('/divisions/{id}', 'App\Http\Controllers\DivisionController@update')->name('divisions.update');
    Route::delete('/divisions/{id}', 'App\Http\Controllers\DivisionController@destroy')->name('divisions.destroy');



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
    // master routes
    Route::get('/master', 'App\Http\Controllers\MasterController@index')->name('master.index');
    Route::get('/master/create', 'App\Http\Controllers\MasterController@create')->name('master.create');
    ///search-text-task'
    Route::any('/search-text-task', 'App\Http\Controllers\MasterController@search_text_task')->name('master.search_text_task');
    Route::post('/master', 'App\Http\Controllers\MasterController@store')->name('master.store');
    Route::get('/master/{id}', 'App\Http\Controllers\MasterController@show')->name('master.show');
    Route::get('/master/{id}/edit', 'App\Http\Controllers\MasterController@edit')->name('master.edit');
    // step1
    Route::get('/master/{id}/step1', 'App\Http\Controllers\MasterController@step1')->name('master.step1');
    // step2
    Route::post('/master/{id}/step2', 'App\Http\Controllers\MasterController@step2')->name('master.step2');
    // step3
    Route::get('/master/{id}/step3', 'App\Http\Controllers\MasterController@step3')->name('master.step3');
    Route::get('/master/{id}/step4', 'App\Http\Controllers\MasterController@step4')->name('master.step4');
    Route::post('/master/{id}/step5', 'App\Http\Controllers\MasterController@step5')->name('master.step5');
    // post briefing
    Route::post('/masterbriefing', 'App\Http\Controllers\MasterController@briefing')->name('masterbriefing');
    //master_running
    Route::get('/master_running', 'App\Http\Controllers\MasterController@running')->name('master_running');
    // master/{id}ending
    Route::post('/master/{id}/ending', 'App\Http\Controllers\MasterController@ending')->name('master_ending');
    // mastercontrols
     
    Route::put('/master/{id}', 'App\Http\Controllers\MasterController@update')->name('master.update');
    Route::delete('/master/{id}', 'App\Http\Controllers\MasterController@destroy')->name('master.destroy');
    // categories routes
    Route::get('/categories', 'App\Http\Controllers\CategoryController@index')->name('cats.index');
    Route::get('/categories/create', 'App\Http\Controllers\CategoryController@create')->name('cats.create');
    Route::post('/categories', 'App\Http\Controllers\CategoryController@store')->name('cats.store');
    Route::get('/categories/{id}', 'App\Http\Controllers\CategoryController@show')->name('cats.show');
    Route::get('/categories/{id}/edit', 'App\Http\Controllers\CategoryController@edit')->name('cats.edit');
    Route::put('/categories/{id}', 'App\Http\Controllers\CategoryController@update')->name('cats.update');
    Route::delete('/categories/{id}', 'App\Http\Controllers\CategoryController@destroy')->name('cats.destroy');
    //  archives routes
    Route::get('/archives', 'App\Http\Controllers\ArchiveController@index')->name('archives.index');
    Route::get('/archives/create', 'App\Http\Controllers\ArchiveController@create')->name('archives.create');
    Route::post('/archives', 'App\Http\Controllers\ArchiveController@store')->name('archives.store');
    Route::get('/archives/{id}', 'App\Http\Controllers\ArchiveController@show')->name('archives.show');
    Route::get('/archives/{id}/edit', 'App\Http\Controllers\ArchiveController@edit')->name('archives.edit');
    Route::put('/archives/{id}', 'App\Http\Controllers\ArchiveController@update')->name('archives.update');
    Route::delete('/archives/{id}', 'App\Http\Controllers\ArchiveController@destroy')->name('archives.destroy');
     // Organomic
    Route::get('/organomic', 'App\Http\Controllers\OrganomicController@index')->name('organomic.index');
    Route::get('/organomic/create', 'App\Http\Controllers\OrganomicController@create')->name('organomic.create');
    Route::post('/organomic', 'App\Http\Controllers\OrganomicController@store')->name('organomic.store');
    Route::get('/organomic/{id}', 'App\Http\Controllers\OrganomicController@show')->name('organomic.show');
    Route::get('/organomic/{id}/edit', 'App\Http\Controllers\OrganomicController@edit')->name('organomic.edit');
    Route::put('/organomic/{id}', 'App\Http\Controllers\OrganomicController@update')->name('organomic.update');
    Route::delete('/organomic/{id}', 'App\Http\Controllers\OrganomicController@destroy')->name('organomic.destroy');

    // doc routes
    Route::get('/docs', 'App\Http\Controllers\DocController@index')->name('docs.index');
    Route::get('/docs/create', 'App\Http\Controllers\DocController@create')->name('docs.create');
    Route::post('/docs', 'App\Http\Controllers\DocController@store')->name('docs.store');
    Route::any('/apistoredocs', 'App\Http\Controllers\DocController@apistoredocs')->name('docs.apistoredocs');
    Route::get('/docs/{id}', 'App\Http\Controllers\DocController@show')->name('docs.show');
    Route::get('/docs/{id}/edit', 'App\Http\Controllers\DocController@edit')->name('docs.edit');
    Route::put('/docs/{id}', 'App\Http\Controllers\DocController@update')->name('docs.update');
    Route::delete('/docs/{id}', 'App\Http\Controllers\DocController@destroy')->name('docs.destroy');
    Route::get('/docsimport', 'App\Http\Controllers\DocController@import')->name('docs.import');
    Route::post('/docsimport', 'App\Http\Controllers\DocController@importData')->name('docs.importData');
    Route::get('/addDocs', 'App\Http\Controllers\DocController@addDocs')->name('docs.addDocs');
    Route::any('/store_to_project', 'App\Http\Controllers\DocController@store_to_project')->name('docs.store_to_project');

    Route::get('/documents/{path}', 'App\Http\Controllers\DocumentController@show')->name('documents.show');

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
    // projects.grantt
    Route::get('/projects_grantt', 'App\Http\Controllers\ProjectController@grantt')->name('projects.grantt');
    // /stage_tasks/${project.id}/${stage.stage_id}
    Route::get('/stage_tasks/{project_id}/{stage_id}', 'App\Http\Controllers\ProjectController@stage_tasks')->name('projects.stage_tasks');
    // stage_tasks_pdf_print/${project.id}/${stage.stage_id}
    Route::get('/stage_tasks_print/{project_id}/{stage_id}', 'App\Http\Controllers\ProjectController@stage_tasks_print')->name('projects.stage_tasks_print');
    
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
    Route::post('/steps', 'App\Http\Controllers\StepController@store')->name('steps.store');
    Route::get('/steps/{id}', 'App\Http\Controllers\StepController@show')->name('steps.show');
    Route::get('/steps/{id}/edit', 'App\Http\Controllers\StepController@edit')->name('steps.edit');
    Route::put('/steps/{id}', 'App\Http\Controllers\StepController@update')->name('steps.update');
    Route::delete('/steps/{id}', 'App\Http\Controllers\StepController@destroy')->name('steps.destroy');
    // api_add_step
    Route::any('/api_add_step', 'App\Http\Controllers\StepController@api_add_step')->name('steps.api_add_step');

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
    /// stages.new_steps
    Route::post('/stages_new_steps', 'App\Http\Controllers\StageController@new_steps')->name('stages.new_steps');

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

    // buildings routes
    Route::get('/buildings', 'App\Http\Controllers\BuildingController@index')->name('buildings.index');
    Route::get('/buildings/create', 'App\Http\Controllers\BuildingController@create')->name('buildings.create');
    Route::post('/buildings', 'App\Http\Controllers\BuildingController@store')->name('buildings.store');
    Route::get('/buildings/{id}', 'App\Http\Controllers\BuildingController@show')->name('buildings.show');
    Route::get('/buildings/{id}/edit', 'App\Http\Controllers\BuildingController@edit')->name('buildings.edit');
    Route::put('/buildings/{id}', 'App\Http\Controllers\BuildingController@update')->name('buildings.update');
    Route::delete('/buildings/{id}', 'App\Http\Controllers\BuildingController@destroy')->name('buildings.destroy');
    // import data from csv file
    Route::get('/buildingsimport', 'App\Http\Controllers\BuildingController@import')->name('buildings.import');
    Route::post('/buildingsimport', 'App\Http\Controllers\BuildingController@importData')->name('buildings.importData');


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
    ///search-nomenclatures
    Route::any('/search-nomenclatures', 'App\Http\Controllers\NomenclatureController@search')->name('nomenclatures.search');
    // add-nomenclature-to-project
    Route::any('/add-nomenclature-to-project', 'App\Http\Controllers\NomenclatureController@addNomenclatureToProject')->name('nomenclatures.addNomenclatureToProject');
    
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
    //addimg
    Route::post('/taskimgs/{id}/create', 'App\Http\Controllers\TaskController@createImg')->name('tasksimg.create');
    //Tasks clear
    Route::get('/task_clear', 'App\Http\Controllers\TaskController@clear')->name('tasks.clear');
    //tasks.problem
    Route::post('/tasks_problem', 'App\Http\Controllers\TaskController@problem')->name('tasks.problem');
    // tasks.show_today
    Route::get('/tasks_show_today', 'App\Http\Controllers\TaskController@show_today')->name('tasks.show_today');
  
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

    // magasines
    Route::get('/magasines', 'App\Http\Controllers\MagasinController@index')->name('magasines.index');
    Route::get('/magasines/create', 'App\Http\Controllers\MagasinController@create')->name('magasines.create');
    Route::post('/magasines', 'App\Http\Controllers\MagasinController@store')->name('magasines.store');
    Route::get('/magasines/{id}', 'App\Http\Controllers\MagasinController@show')->name('magasines.show');
    Route::get('/magasines/{id}/edit', 'App\Http\Controllers\MagasinController@edit')->name('magasines.edit');
    Route::put('/magasines/{id}', 'App\Http\Controllers\MagasinController@update')->name('magasines.update');
    Route::delete('/magasines/{id}', 'App\Http\Controllers\MagasinController@destroy')->name('magasines.destroy');
    // products
    Route::get('/products', 'App\Http\Controllers\ProductController@index')->name('products.index');
    Route::get('/products/create', 'App\Http\Controllers\ProductController@create')->name('products.create');
    Route::post('/products', 'App\Http\Controllers\ProductController@store')->name('products.store');
    Route::get('/products/{id}', 'App\Http\Controllers\ProductController@show')->name('products.show');
    Route::get('/products/{id}/edit', 'App\Http\Controllers\ProductController@edit')->name('products.edit');
    Route::put('/products/{id}', 'App\Http\Controllers\ProductController@update')->name('products.update');
    Route::delete('/products/{id}', 'App\Http\Controllers\ProductController@destroy')->name('products.destroy');
    // equipments
    Route::get('/equipments', 'App\Http\Controllers\EquipmentController@index')->name('equipments.index');
    Route::get('/equipments/create', 'App\Http\Controllers\EquipmentController@create')->name('equipments.create');
    Route::post('/equipments', 'App\Http\Controllers\EquipmentController@store')->name('equipments.store');
    Route::get('/equipments/{id}', 'App\Http\Controllers\EquipmentController@show')->name('equipments.show');
    Route::get('/equipments/{id}/edit', 'App\Http\Controllers\EquipmentController@edit')->name('equipments.edit');
    Route::put('/equipments/{id}', 'App\Http\Controllers\EquipmentController@update')->name('equipments.update');
    Route::delete('/equipments/{id}', 'App\Http\Controllers\EquipmentController@destroy')->name('equipments.destroy');
    // stores
    Route::get('/stores', 'App\Http\Controllers\StoreController@index')->name('stores.index');
    Route::get('/stores/create', 'App\Http\Controllers\StoreController@create')->name('stores.create');
    Route::post('/stores', 'App\Http\Controllers\StoreController@store')->name('stores.store');
    Route::get('/stores/{id}', 'App\Http\Controllers\StoreController@show')->name('stores.show');
    Route::get('/stores/{id}/edit', 'App\Http\Controllers\StoreController@edit')->name('stores.edit');
    Route::put('/stores/{id}', 'App\Http\Controllers\StoreController@update')->name('stores.update');
    Route::delete('/stores/{id}', 'App\Http\Controllers\StoreController@destroy')->name('stores.destroy');

    //rooms 
    Route::get('/rooms', 'App\Http\Controllers\RoomController@index')->name('rooms.index');
    Route::get('/rooms/create', 'App\Http\Controllers\RoomController@create')->name('rooms.create');
    Route::post('/rooms', 'App\Http\Controllers\RoomController@store')->name('rooms.store');
    Route::get('/rooms/{id}', 'App\Http\Controllers\RoomController@show')->name('rooms.show');
    Route::get('/rooms/{id}/edit', 'App\Http\Controllers\RoomController@edit')->name('rooms.edit');
    Route::put('/rooms/{id}', 'App\Http\Controllers\RoomController@update')->name('rooms.update');
    Route::delete('/rooms/{id}', 'App\Http\Controllers\RoomController@destroy')->name('rooms.destroy');
    // roles 
    Route::get('/roles', 'App\Http\Controllers\RoleController@index')->name('roles.index');
    Route::get('/roles/create', 'App\Http\Controllers\RoleController@create')->name('roles.create');
    Route::post('/roles', 'App\Http\Controllers\RoleController@store')->name('roles.store');
    Route::get('/roles/{id}', 'App\Http\Controllers\RoleController@show')->name('roles.show');
    Route::get('/roles/{id}/edit', 'App\Http\Controllers\RoleController@edit')->name('roles.edit');
    Route::put('/roles/{id}', 'App\Http\Controllers\RoleController@update')->name('roles.update');
    Route::delete('/roles/{id}', 'App\Http\Controllers\RoleController@destroy')->name('roles.destroy');

    // mag 
    Route::get('/mag', 'App\Http\Controllers\MagController@index')->name('mag.index');
    Route::get('/mag/create', 'App\Http\Controllers\MagController@create')->name('mag.create');
    Route::post('/mag', 'App\Http\Controllers\MagController@store')->name('mag.store');
    Route::get('/mag/{id}', 'App\Http\Controllers\MagController@show')->name('mag.show');
    Route::get('/mag/{id}/edit', 'App\Http\Controllers\MagController@edit')->name('mag.edit');
    Route::put('/mag/{id}', 'App\Http\Controllers\MagController@update')->name('mag.update');
    Route::delete('/mag/{id}', 'App\Http\Controllers\MagController@destroy')->name('mag.destroy');
    // mag.storeRow
    Route::post('/mag/storeRow', 'App\Http\Controllers\MagController@storeRow')->name('mag.storeRow');
    // mag.chart
    Route::get('/mag/{id}/chart', 'App\Http\Controllers\MagController@chart')->name('mag.chart');

    // forms
    Route::get('/forms', 'App\Http\Controllers\FormController@index')->name('forms.index');
    Route::get('/forms/create', 'App\Http\Controllers\FormController@create')->name('forms.create');
    Route::post('/forms', 'App\Http\Controllers\FormController@store')->name('forms.store');
    Route::get('/forms/{id}', 'App\Http\Controllers\FormController@show')->name('forms.show');
    Route::get('/forms/{id}/edit', 'App\Http\Controllers\FormController@edit')->name('forms.edit');
    Route::put('/forms/{id}', 'App\Http\Controllers\FormController@update')->name('forms.update');
    Route::delete('/forms/{id}', 'App\Http\Controllers\FormController@destroy')->name('forms.destroy');
    // items
    Route::get('/items', 'App\Http\Controllers\ItemController@index')->name('items.index');
    Route::get('/items/create', 'App\Http\Controllers\ItemController@create')->name('items.create');
    Route::post('/items', 'App\Http\Controllers\ItemController@store')->name('items.store');
    Route::get('/items/{id}', 'App\Http\Controllers\ItemController@show')->name('items.show');
    Route::get('/items/{id}/edit', 'App\Http\Controllers\ItemController@edit')->name('items.edit');
    Route::put('/items/{id}', 'App\Http\Controllers\ItemController@update')->name('items.update');
    Route::delete('/items/{id}', 'App\Http\Controllers\ItemController@destroy')->name('items.destroy');

    // callings
    Route::get('/callings', 'App\Http\Controllers\CallingController@index')->name('callings.index');
    Route::get('/callings/create', 'App\Http\Controllers\CallingController@create')->name('callings.create');
    Route::get('/confirmSS/{id}', 'App\Http\Controllers\CallingController@confirmSS')->name('callings.confirmSS');
    // post confirmSS
    Route::post('/confirmStore', 'App\Http\Controllers\CallingController@confirmStore')->name('callings.confirmStore');
    Route::get('/confirm/{id}', 'App\Http\Controllers\CallingController@confirm')->name('callings.confirm');
    // post confirm
    Route::post('/confirmStoreCh', 'App\Http\Controllers\CallingController@confirmStoreCh')->name('callings.confirmStoreCh');
    Route::post('/callings', 'App\Http\Controllers\CallingController@store')->name('callings.store');
    Route::get('/callings/{id}', 'App\Http\Controllers\CallingController@show')->name('callings.show');
    Route::get('/callings/{id}/edit', 'App\Http\Controllers\CallingController@edit')->name('callings.edit');
    Route::put('/callings/{id}', 'App\Http\Controllers\CallingController@update')->name('callings.update');
    Route::delete('/callings/{id}', 'App\Http\Controllers\CallingController@destroy')->name('callings.destroy');

    // risk
    Route::any('/risks', 'App\Http\Controllers\RiskController@index')->name('risks.index');
    Route::get('/risks/create', 'App\Http\Controllers\RiskController@create')->name('risks.create');
    Route::post('/risks', 'App\Http\Controllers\RiskController@store')->name('risks.store');
    Route::get('/risks/{id}', 'App\Http\Controllers\RiskController@show')->name('risks.show');
    Route::get('/risks/{id}/edit', 'App\Http\Controllers\RiskController@edit')->name('risks.edit');
    Route::put('/risks/{id}', 'App\Http\Controllers\RiskController@update')->name('risks.update');
    Route::delete('/risks/{id}', 'App\Http\Controllers\RiskController@destroy')->name('risks.destroy');
    //experiences
    Route::get('/experiences', 'App\Http\Controllers\RiskController@experiences')->name('experiences');
    
    
    
    

});







