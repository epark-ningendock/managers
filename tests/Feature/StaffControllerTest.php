<?php

namespace Tests\Feature;

use App\StaffAuth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StaffControllerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Test Staff List
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/staff');
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testCreate()
    {
        $response = $this->call('GET', '/staff/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEdit()
    {
        $staff_auth = factory(StaffAuth::class, 'with_staff')->create();
        $response = $this->call('GET', "/staff/$staff_auth->staff_id/edit");
        $this->assertEquals(200, $response->getStatusCode());
    }
}
