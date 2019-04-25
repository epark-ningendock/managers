<?php

namespace Tests\Feature;

use App\MajorClassification;
use App\MiddleClassification;
use App\MinorClassification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ClassificationInputFieldsTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    public function testDeleteMinorClassification()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $response = $this->delete("/classification/$minor->id?classification=minor", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('minor_classifications', [ 'id' => $minor->id]);
    }

    public function testDeleteMiddleClassificationValidation()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $middle = $minor->middle_classification;
        $response = $this->delete("/classification/$middle->id?classification=middle", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas('error');
    }

    public function testDeleteMiddleClassification()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $middle = $minor->middle_classification;
        $response = $this->delete("/classification/$middle->id?classification=middle", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('middle_classifications', [ 'id' => $middle->id]);
    }

    public function testDeleteMajorClassificationValidation()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $major = $minor->major_classification;
        $response = $this->delete("/classification/$major->id?classification=major", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas('error');
    }

    public function testDeleteMajorClassification()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $minor->middle_classification->delete();
        $major = $minor->major_classification;
        $response = $this->delete("/classification/$major->id?classification=major", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('major_classifications', [ 'id' => $major->id]);
    }

    public function testUpdateMajorClassificationSort()
    {
        $majors = factory(MajorClassification::class, 'with_type', 5)->create();
        $ids = $majors->map(function($major) {
                   return $major->id;
                })->toArray();
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'major', 'classification_ids' => $ids]);
        $this->assertEquals(302, $response->getStatusCode());
        foreach ($majors as $i => $major) {
            $major->refresh();
            self::assertEquals($i + 1, $major->order);
        }

    }


    public function testInvalidIdsInUpdateSort()
    {
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'minor', 'classification_ids' => $this->faker->userName]);
        $response->assertSessionHasErrors( 'classification_ids' );

    }

    public function testNonIntegerIdInUpdateSort()
    {
        $ids = $this->faker->words();
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'minor', 'classification_ids' => $this->faker->userName]);
        $response->assertSessionHasErrors( 'classification_ids' );

    }

    public function testWrongIdInUpdateSort()
    {
        $ids = [900, 901, 902];
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'minor', 'classification_ids' => $ids]);
        $response->assertSessionHas( 'error' );

    }

    public function testRequireIdsInUpdateSort()
    {
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'minor']);
        $response->assertSessionHasErrors( 'classification_ids' );

    }

    public function testUpdateMiddleClassificationSort()
    {
        $middles = factory(MiddleClassification::class, 'with_major', 5)->create();
        $ids = $middles->map(function($middle) {
            return $middle->id;
        })->toArray();
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'middle', 'classification_ids' => $ids]);
        $this->assertEquals(302, $response->getStatusCode());
        foreach ($middles as $i => $middle) {
            $middle->refresh();
            self::assertEquals($i + 1, $middle->order);
        }

    }

    public function testUpdateMinorClassificationSort()
    {
        $minors = factory(MinorClassification::class, 'with_major_middle', 5)->create();
        $ids = $minors->map(function($minor) {
            return $minor->id;
        })->toArray();
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification' => 'minor', 'classification_ids' => $ids]);
        $this->assertEquals(302, $response->getStatusCode());
        foreach ($minors as $i => $minor) {
            $minor->refresh();
            self::assertEquals($i + 1, $minor->order);
        }

    }

    public function testRequireClassificationInUpdateSort()
    {
        $minors = factory(MinorClassification::class, 'with_major_middle', 5)->create();
        $ids = $minors->map(function($minor) {
            return $minor->id;
        })->toArray();
        $response = $this->patch("/classification/sort/update",
            ['_token' => csrf_token(), 'classification_ids' => $ids]);
        $response->assertSessionHasErrors( 'classification' );

    }
}
