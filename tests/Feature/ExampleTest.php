<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_core_public_pages_and_api_are_available(): void
    {
        $this->get('/projects')->assertOk();
        $this->get('/articles')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/api/projects')->assertOk()->assertJsonCount(4, 'data');
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
