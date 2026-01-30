<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Notes as NoteResource;
use App\Models\Note as NoteModel;

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
    $request->validate(['name' => 'required|max:255', 'content' => 'required']);

    $note = new NoteModel();
    $note->name = $request->post('name');
    $note->content = $request->post('content');
    $note->save();

    return new NoteResource($note);
});

//update a note
Route::put('/notes/{id}', function ($id, Request $request) {
    $request->validate(['name' => 'required|max:255', 'content' => 'required']);
    $note = NoteModel::findOrFail($id);

    $note->name = $request->post('name');
    $note->content = $request->post('content');

    $note->save();

    return new NoteResource($note);
});

//delete a note
Route::delete('/notes/{id}', function ($id) {
    $note = NoteModel::findOrFail($id);
    $note->delete();
});
