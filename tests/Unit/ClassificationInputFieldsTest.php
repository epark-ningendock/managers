<?php

namespace Tests\Feature;

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
        $response = $this->call('DELETE', "/classification/$minor->id?classification=minor", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('minor_classifications', [ 'id' => $minor->id]);
    }

    public function testDeleteMiddleClassificationValidation()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $middle = $minor->middle_classification;
        $response = $this->call('DELETE', "/classification/$middle->id?classification=middle", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas('error');
    }

    public function testDeleteMiddleClassification()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $middle = $minor->middle_classification;
        $response = $this->call('DELETE', "/classification/$middle->id?classification=middle", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('middle_classifications', [ 'id' => $middle->id]);
    }

    public function testDeleteMajorClassificationValidation()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $major = $minor->major_classification;
        $response = $this->call('DELETE', "/classification/$major->id?classification=major", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas('error');
    }

    public function testDeleteMajorClassification()
    {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $minor->delete();
        $minor->middle_classification->delete();
        $major = $minor->major_classification;
        $response = $this->call('DELETE', "/classification/$major->id?classification=major", ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertSoftDeleted('major_classifications', [ 'id' => $major->id]);
    }
}
