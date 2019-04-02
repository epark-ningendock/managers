<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffControllerTest extends TestCase
{
    /**
     * Test Staff List
     *
     * @return void
     */
    public function testStaffList()
    {
        $response = $this->call('GET', '/staff');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
