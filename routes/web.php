<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Symfony\Component\Routing\Loader\Configurator\Routes;

//Auth routs - use no logged
Route::middleware([CheckIsNotLogged::class])->group(function () {
    //Essas duas rotas irão acontecer se o usuário não existir ou não estiver logado
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit']);
});

//app routes - user logged
Route::middleware([CheckIsLogged::class])->group(function(){
    Route::get('/', [MainController::class, 'index' ])->name('home');
    Route::get('/newNote', [MainController::class, 'newNote' ])->name('new');

    Route::post('/newNoteSubmit', [MainController::class, 'newNoteSubmit'])->name('newNoteSubmit');

    //Edit note
    Route::get('/editNote/{id}', [MainController::class, 'editNote'])->name('edit');
    Route::post('/editNoteSubmit}', [MainController::class, 'editNoteSubmit'])->name('editNoteSubmit');


    //Delete note
    Route::get('/deleteNote/{id}', [MainController::class, 'deleteNote'])->name('delete');

    Route::get('/logout', [AuthController::class, 'logout' ])->name('logout');
});

//estas só vão ser executadas se o usuário estiver logado





