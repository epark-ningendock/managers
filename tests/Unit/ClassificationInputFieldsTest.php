<?php

namespace Tests\Feature;

use App\ClassificationType;
use App\MajorClassification;
use App\MiddleClassification;
use App\MinorClassification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
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

    public function testRequireClassificationType()
    {
        $this->validateMajorFields(['classification_type_id' => null])->assertSessionHasErrors('classification_type_id');
    }

    public function testInvalidClassificationType()
    {
        $this->validateMajorFields(['classification_type_id' => 9000])->assertSessionHasErrors('classification_type_id');
    }

    public function testRequireName()
    {
        $this->validateMajorFields(['name' => null])->assertSessionHasErrors('name');
    }

    public function testInvalidName()
    {
        $this->validateMajorFields(['name' => Str::random(101)])->assertSessionHasErrors('name');
    }

    public function testRequireMajorClassificationId()
    {
        $this->validateMiddleFields(['major_classification_id' => null])->assertSessionHasErrors('major_classification_id');
    }

    public function testInvalidMajorClassificationId()
    {
        $this->validateMiddleFields(['major_classification_id' => 9000])->assertSessionHasErrors('major_classification_id');
    }

    public function testRequireMiddleClassificationId()
    {
        $this->validateMinorFields(['middle_classification_id' => null])->assertSessionHasErrors('middle_classification_id');
    }

    public function testInvalidMinorClassificationId()
    {
        $this->validateMinorFields(['middle_classification_id' => 9000])->assertSessionHasErrors('middle_classification_id');
    }

    public function testRequireIsIcon()
    {
        $this->validateMiddleFields(['is_icon' => null])->assertSessionHasErrors('is_icon');
    }

    public function testInvalidIsIcon()
    {
        $this->validateMiddleFields(['is_icon' => '10'])->assertSessionHasErrors('is_icon');
    }

    public function testRequireIconName()
    {
        $this->validateMiddleFields(['is_icon' => '1', 'icon_name' => null])->assertSessionHasErrors('icon_name');
    }

    public function testInvalidIconName()
    {
        $this->validateMiddleFields(['is_icon' => '1', 'icon_name' => Str::random(101)])->assertSessionHasErrors('icon_name');
    }

    public function testRequireIsFregist()
    {
        $this->validateMinorFields(['is_fregist' => null])->assertSessionHasErrors('is_fregist');
    }

    public function testInvalidIsFregist()
    {
        $this->validateMinorFields(['is_fregist' => '10'])->assertSessionHasErrors('is_fregist');
    }

    public function testRequireMaxLength()
    {
        $this->validateMinorFields(['is_fregist' => '0', 'max_length' => null])->assertSessionHasErrors('max_length');
    }

    public function testInvalidMaxLength()
    {
        $this->validateMinorFields(['is_fregist' => '0', 'max_length' => 10000])->assertSessionHasErrors('max_length');
    }

    public function testRequireStatus()
    {
        $this->validateMajorFields(['status' => null])->assertSessionHasErrors('status');
    }

    public function testInvalidStatus()
    {
        $this->validateMajorFields(['status' => '5'])->assertSessionHasErrors('status');
    }

    function testCreateMajorClassification()
    {
        $response = $this->call('POST', 'classification', $this->validMajorFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    function testCreateMiddleClassification()
    {
        $response = $this->call('POST', 'classification', $this->validMiddleFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    function testCreateMinorClassification()
    {
        $response = $this->call('POST', 'classification', $this->validMinorFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    function testUpdateMajorClassification() {
        $major = factory(MajorClassification::class, 'with_type')->create();
        $response = $this->put( "/classification/{$major->id}",  $this->validMajorFields());
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    function testUpdateMiddleClassification() {
        $middle = factory(MiddleClassification::class, 'with_major')->create();
        $response = $this->put( "/classification/{$middle->id}",  $this->validMiddleFields());
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    function testUpdateMinorClassification() {
        $minor = factory(MinorClassification::class, 'with_major_middle')->create();
        $response = $this->put( "/classification/{$minor->id}",  $this->validMinorFields());
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    /**
     * Major Classification fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validMajorFields($overwrites = [])
    {
        $type = factory(ClassificationType::class)->create();
        $fields = factory(MajorClassification::class)->raw();
        $fields['classification_type'] = $type->id;
        $fields['_token'] = csrf_token();
        $fields['classification'] = 'major';
        return array_merge($fields, $overwrites);
    }

    /**
     * Middle Classification fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validMiddleFields($overwrites = [])
    {
        $major = factory(MajorClassification::class, 'with_type')->create();
        $fields = factory(MiddleClassification::class)->raw();
        $fields['major_classification_id'] = $major->id;
        $fields['_token'] = csrf_token();
        $fields['classification'] = 'middle';
        return array_merge($fields, $overwrites);
    }

    /**
     * Minor Classification fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validMinorFields($overwrites = [])
    {
        $middle = factory(MiddleClassification::class, 'with_major')->create();
        $fields = factory(MinorClassification::class)->raw();
        $fields['major_classification_id'] = $middle->major_classification_id;
        $fields['middle_classification_id'] = $middle->id;
        $fields['_token'] = csrf_token();
        $fields['classification'] = 'minor';
        return array_merge($fields, $overwrites);
    }


    /**
     * validate major classification fields process
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateMajorFields($attributes)
    {
        $attributes['classification'] = 'major';
        $this->withExceptionHandling();
        return $this->post('/classification', $this->validMajorFields($attributes));
    }

    /**
     * validate middle classification fields process
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateMiddleFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->post('/classification', $this->validMiddleFields($attributes));
    }

    /**
     * validate minor classification fields process
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateMinorFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->post('/classification', $this->validMinorFields($attributes));
    }

}
