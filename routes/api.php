<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Notes as NoteResource;
use App\Models\Note as NoteModel;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

//get all notes
Route::get('/notes', function () {
    return NoteResource::collection(NoteModel::all());
});

//get a single note
Route::get('/notes/{id}', function ($id) {
    return new NoteResource(NoteModel::findOrFail($id));
});

//create a note
Route::post('/notes', function (Request $request) {
    $note = new NoteModel();
    $note->name = $request->post('name');
    $note->content = $request->post('content');
    $note->save();

});

//update a note
Route::put('/notes/{id}', function ($id, Request $request) {
    $note = NoteModel::findOrFail($id);

    $note->name = $request->post('name');
    $note->content = $request->post('content');

    $note->save();
});

//delete a note
Route::delete('/notes/{id}', function ($id) {
    $note = NoteModel::findOrFail($id);
    $note->delete();
});
