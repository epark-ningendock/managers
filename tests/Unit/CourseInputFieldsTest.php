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

    public function testInvalidIdsInUpdateSort()
    {
        $response = $this->patch("/course/sort/update",
            ['_token' => csrf_token(), 'course_ids' => $this->faker->userName]);
        $response->assertSessionHasErrors( 'course_ids' );

    }

    public function testNonIntegerIdInUpdateSort()
    {
        $ids = $this->faker->words();
        $response = $this->patch("/course/sort/update",
            ['_token' => csrf_token(), 'course_ids' => $this->faker->userName]);
        $response->assertSessionHasErrors( 'course_ids' );

    }

    public function testWrongIdInUpdateSort()
    {
        $ids = [900, 901, 902];
        $response = $this->patch("/course/sort/update",
            ['_token' => csrf_token(), 'course_ids' => $ids]);
        $response->assertSessionHas( 'error' );

    }

    public function testRequireIdsInUpdateSort()
    {
        $response = $this->patch("/course/sort/update",
            ['_token' => csrf_token()]);
        $response->assertSessionHasErrors( 'course_ids' );

    }

    public function testUpdateSort()
    {
        $details = factory(CourseDetail::class, 'with_all', 5)->create();
        $ids = $details->map(function($detail) {
            return $detail->course_id;
        })->toArray();
        $response = $this->patch("/course/sort/update",
            ['_token' => csrf_token(), 'course_ids' => $ids]);
        $this->assertEquals(302, $response->getStatusCode());
        foreach ($details as $i => $detail) {
            $course = $detail->course;
            $course->refresh();
            self::assertEquals($i + 1, $course->order);
        }

    }
}
