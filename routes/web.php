<?php


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/notes/edit/', function () {
    return Inertia::render('NoteEdit');
})->name('notecreate');

Route::get('/notes/edit/{id}', function (int $id) {
    return Inertia::render('NoteEdit',
        ['id' => $id]);
})->name('noteedit');

Route::get('/notes', function () {
    return Inertia::render('Notes');
})->name('notes');

Route::get('/notes/{id}', function (int $id) {
    return Inertia::render('NoteView', ['id' => $id]);
})->name('noteview');
