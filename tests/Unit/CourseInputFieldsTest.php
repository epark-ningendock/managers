<?php

namespace Tests\Feature;

use App\Course;
use App\CourseDetail;
use App\Enums\Status;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CourseInputFieldsTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function testDeleteCourse()
    {
        $course = factory(CourseDetail::class, 'with_all')->create()->course;
        $response = $this->call('DELETE', "/course/$course->id", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('courses', [ 'id' => $course->id]);

    }
}
