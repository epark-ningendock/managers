<?php

namespace Tests\Feature;

use App\MajorClassification;
use App\MiddleClassification;
use App\MinorClassification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use App\HospitalStaff;

class ClassificationControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        Session::start();

        //authentication
        $hospital_staff = factory(HospitalStaff::class)->create();
        $this->be($hospital_staff);
    }

    /**
     * Test Classification List
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/classification');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Classification Sort List
     * @return void
     */
    public function testSoft()
    {
        $response = $this->call('GET', '/classification/sort');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Major Classification Edit
     * @return void
     */
    public function testMajorClassificationEdit()
    {
        $major = factory(MajorClassification::class, 'with_type')->create();
        $response = $this->call('GET', '/classification/'.$major->id.'/edit?classification=major');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Middle Classification Edit
     * @return void
     */
    public function testMiddleClassificationEdit()
    {
        $middle = factory(MiddleClassification::class, 'with_major')->create();
        $response = $this->call('GET', '/classification/'.$middle->id.'/edit?classification=middle');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Minor Classification Edit
     * @return void
     */
    public function testMinorClassificationEdit()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $response = $this->call('GET', '/classification/'.$minor->id.'/edit?classification=minor');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
