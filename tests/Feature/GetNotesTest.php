<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetNotesTest extends TestCase
{

    use RefreshDatabase;
    /**
     * Get some notes from the database
     */
    public function testGetNotes(): void
    {
        $this->seed();

        $url = '/api/notes';
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        /**
         * $response TestResponse
         */
        $response = $this->getJson($url, $headers);

        $structure = [
            'data' => [
                '*' => [
                    'name', 'content', 'id', 'created_at', 'updated_at'
                ]
            ]
        ];

        $response->assertStatus(200)
            ->assertJsonStructure($structure);
    }
}
