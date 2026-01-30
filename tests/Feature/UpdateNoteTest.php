<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * update a note
     */
    public function testUpdateValidNote(): void
    {
        $this->seed();
        //get a valid note ID
        $note = Note::where('id', '!=', 0)->firstOrFail();

        $id = $note->id;

        $url = '/api/notes/'.$id;
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $body = [
            'name' => 'test example',
            'content' => 'remember to finish writing all the tests'
        ];

        /**
         * $response TestResponse
         */
        $response = $this->putJson($url, $body, $headers);
        $structure = [
            'data' =>
                ['name', 'content', 'id', 'created_at', 'updated_at']
        ];

        $response->assertStatus(200)
            ->assertJsonStructure($structure);
    }

    /**
     * update an invalid note with mistakes
     * @return void
     */
    public function testUpdateInvalidNote(): void
    {
        $this->seed();
        //get a valid note ID
        $note = Note::where('id', '!=', 0)->firstOrFail();

        $id = $note->id;

        $url = '/api/notes/'.$id;

        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $body = [
        ];

        /**
         * $response TestResponse
         */
        $response = $this->putJson($url, $body, $headers);
        $structure = [
            'message',
            'errors' => [
                'name', 'content'
            ]
        ];

        $response->assertStatus(422)
            ->assertJsonStructure($structure);
    }

    /**
     * update an invalid note with mistakes
     * @return void
     */
    public function testUpdateNonExistantNote(): void
    {
        $this->seed();

        $id = -99;

        $url = '/api/notes/'.$id;

        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $body = [
            'name' => 'test example',
            'content' => 'remember to finish writing all the tests'
        ];

        /**
         * $response TestResponse
         */
        $response = $this->putJson($url, $body, $headers);
        $structure = [
            'message',
        ];

        $response->assertStatus(404)
            ->assertJsonStructure($structure);
    }
}
