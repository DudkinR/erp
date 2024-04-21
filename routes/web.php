<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

// structure routes
Route::get('/structure', 'App\Http\Controllers\StructureController@index')->name('structure.index');
Route::get('/structure/create', 'App\Http\Controllers\StructureController@create')->name('structure.create');
Route::post('/structure', 'App\Http\Controllers\StructureController@store')->name('structure.store');
Route::get('/structure/{id}', 'App\Http\Controllers\StructureController@show')->name('structure.show');
Route::get('/structure/{id}/edit', 'App\Http\Controllers\StructureController@edit')->name('structure.edit');
Route::put('/structure/{id}', 'App\Http\Controllers\StructureController@update')->name('structure.update');
Route::delete('/structure/{id}', 'App\Http\Controllers\StructureController@destroy')->name('structure.destroy');

// criteria routes
Route::get('/criteria', 'App\Http\Controllers\CriteriaController@index')->name('criteria.index');
Route::get('/criteria/create', 'App\Http\Controllers\CriteriaController@create')->name('criteria.create');
Route::post('/criteria', 'App\Http\Controllers\CriteriaController@store')->name('criteria.store');
Route::get('/criteria/{id}', 'App\Http\Controllers\CriteriaController@show')->name('criteria.show');
Route::get('/criteria/{id}/edit', 'App\Http\Controllers\CriteriaController@edit')->name('criteria.edit');
Route::put('/criteria/{id}', 'App\Http\Controllers\CriteriaController@update')->name('criteria.update');
Route::delete('/criteria/{id}', 'App\Http\Controllers\CriteriaController@destroy')->name('criteria.destroy');

// facts routes
Route::get('/facts', 'App\Http\Controllers\FactController@index')->name('facts.index');
Route::get('/facts/create', 'App\Http\Controllers\FactController@create')->name('facts.create');
Route::post('/facts', 'App\Http\Controllers\FactController@store')->name('facts.store');
Route::get('/facts/{id}', 'App\Http\Controllers\FactController@show')->name('facts.show');
Route::get('/facts/{id}/edit', 'App\Http\Controllers\FactController@edit')->name('facts.edit');
Route::put('/facts/{id}', 'App\Http\Controllers\FactController@update')->name('facts.update');
Route::delete('/facts/{id}', 'App\Http\Controllers\FactController@destroy')->name('facts.destroy');





