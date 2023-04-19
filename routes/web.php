<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function(){
    //Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/home', 'App\Http\Livewire\ControleAcesso')->name('home');

    Route::get('/pessoas',  [App\Http\Controllers\PessoasController::class, 'index'])->name('pessoas.index');
    Route::get('/pessoas/create',  [App\Http\Controllers\PessoasController::class, 'create'])->name('pessoas.create');
    Route::post('/pessoas/store',  [App\Http\Controllers\PessoasController::class, 'store'])->name('pessoas.store');
    Route::get('/pessoas/edit/{id}',  [App\Http\Controllers\PessoasController::class, 'edit'])->name('pessoas.edit');
    Route::put('/pessoas/edit',  [App\Http\Controllers\PessoasController::class, 'update'])->name('pessoas.update');
    Route::get('/pessoas/destroy/{id}', [App\Http\Controllers\PessoasController::class, 'destroy'])->name('pessoas.destroy');

    Route::get('/permissoes',  [App\Http\Controllers\PermissaoController::class, 'index'])->name('permissao.index');
    Route::get('/permissoes/create',  [App\Http\Controllers\PermissaoController::class, 'create'])->name('permissao.create');
    Route::post('/permissoes/store',  [App\Http\Controllers\PermissaoController::class, 'store'])->name('permissao.store');
    Route::get('/permissoes/destroy/{id}', [App\Http\Controllers\PermissaoController::class, 'destroy'])->name('permissao.destroy');

});