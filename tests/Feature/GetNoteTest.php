<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Note;

class GetNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Get a note that exists
     */
    public function testGetExistingNote(): void
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
        $response = $this->getJson($url, $headers);
        $structure = [
            'data' =>
                ['name', 'content', 'id', 'created_at', 'updated_at']
        ];

        $response->assertStatus(200)
            ->assertJsonStructure($structure);
    }

    /**
     * Get a note that exists
     */
    public function testGetNotExistingNote(): void
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

        $structure = [
            'data' =>
                ['name', 'content', 'id', 'created_at', 'updated_at']
        ];

        $response->assertStatus(404);
    }
}
