<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class CourseControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Classification List
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/course');
        $this->assertEquals(200, $response->getStatusCode());

    }
}
