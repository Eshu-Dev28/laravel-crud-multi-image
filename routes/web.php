<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('item', ItemController::class);
Route::delete('/images/{id}', [ImageController::class, 'delete'])->name('image.delete');
