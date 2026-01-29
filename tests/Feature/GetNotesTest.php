<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetNotesTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function getNotes(): void
    {
        $url = '/api/notes';

        $response = $this->get($url);

        $response->assertStatus(200)
            ->assertJsonStructure();
    }
}
