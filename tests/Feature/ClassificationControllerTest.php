<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ClassificationControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Classification List
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/classification');
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testSoft()
    {
        $response = $this->call('GET', '/classification/sort');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
