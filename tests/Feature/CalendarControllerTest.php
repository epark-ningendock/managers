<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test Calendar List
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/calendar');
        $this->assertEquals(200, $response->getStatusCode());

    }
}
