<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * create a note
     */
    public function testCreateValidNote(): void
    {
        $this->seed();
        //get a valid note ID

        $url = '/api/notes/';
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $body = [
            'name' => 'test example',
            'content' => 'remember to finish writing all the tests'
        ];

        /**
         * $response TestResponse
         */
        $response = $this->postJson($url, $body, $headers);
        $structure = [
            'data' =>
                ['name', 'content', 'id', 'created_at', 'updated_at']
        ];

        $response->assertStatus(201)
            ->assertJsonStructure($structure);
    }

    /**
     * create an invalid note
     * @return void
     */
    public function testCreateInvalidNote(): void
    {
        $this->seed();
        //get a valid note ID

        $url = '/api/notes/';
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $body = [
        ];

        /**
         * $response TestResponse
         */
        $response = $this->postJson($url, $body, $headers);
        $structure = [
            'message',
            'errors' => [
                'name', 'content'
            ]
        ];

        $response->assertStatus(422)
            ->assertJsonStructure($structure);
    }
}
