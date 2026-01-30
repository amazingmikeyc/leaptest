<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Get a note that exists
     */
    public function testDeleteExistingNote(): void
    {
        $this->seed();
        //get a valid note ID
        $note = Note::where('id', '!=', 0)->firstOrFail();

        $id = $note->id;

        $url = '/api/notes/'.$id;
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        /**
         * $response TestResponse
         */
        $response = $this->delete($url, $headers);

        $response->assertStatus(200);
    }

    /**
     * Get a note that exists
     */
    public function testDeleteNotExistingNote(): void
    {
        $this->seed();

        //we know there's no minus ones
        $id = '-99';

        $url = '/api/notes/'.$id;
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        /**
         * $response TestResponse
         */
        $response = $this->getJson($url, $headers);

        $response->assertStatus(404);
    }
}
