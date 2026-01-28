<?php


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/notes', function () {
    return Inertia::render('Notes');
})->name('notes');
