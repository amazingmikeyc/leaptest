<?php

namespace App\Models;

use Database\Factories\NotesFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(NotesFactory::class)]
class Note extends Model
{
    use HasFactory;
    //primary keys are ON by default as 'id' in database

    //timestamps - created_on fields - are created by default.

    public $incrementing = true;

}
