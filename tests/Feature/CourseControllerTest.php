<?php

namespace Tests\Feature;

use App\Calendar;
use App\Course;
use App\CourseDetail;
use App\HospitalImage;
use App\CourseQuestion;
use App\CourseOption;
use App\CourseImage;
use App\ImageOrder;
use App\TaxClass;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Hospital;
use App\Option;
use App\MinorClassification;


class CourseControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp()
    {
        parent::setUp();
        Session::start();
    }

    protected function createCourse()
    {
        $course = factory(Course::class, 'with_all')->create();
        $minors = factory(MinorClassification::class, 'with_major_middle', 5)->create();
        $hospital_images = factory(HospitalImage::class, 5)->create(['hospital_id' => $course->hospital_id]);
        $image_orders = factory(ImageOrder::class, 3)->create();
        $options = factory(Option::class, 5)->create(['hospital_id' => $course->hospital_id]);

        foreach ($hospital_images as $image) {
            factory(CourseImage::class)->create([
                'course_id' => $course->id,
                'hospital_image_id' => $image->id,
                'image_order_id' => $this->faker->randomElement($image_orders)->id
            ]);
        }
        foreach ($options as $option) {
            $course_option = new CourseOption();
            $course_option->fill([
                'course_id' => $course->id,
                'option_id' => $option->id
            ]);
            $course_option->save();
        }

        foreach ($minors as $minor) {
            $detail = factory(CourseDetail::class)->make([
                'course_id' => $course->id,
                'minor_classification_id' => $minor->id,
                'middle_classification_id' => $minor->middle_classification_id,
                'major_classification_id' => $minor->major_classification_id
            ]);
            if ($minor->is_fregist == '1') {
                $detail['inputstring'] = null;
                $detail['select_status'] = $this->faker->randomElement([0, 1]);
            } else {
                $detail['inputstring'] = $this->faker->text($minor->max_length);
                $detail['select_status'] = null;
            }
            $detail->save();
        }

        factory(CourseQuestion::class, 5)->create([
            'course_id' => $course->id
        ]);

        return $course;
    }

    /**
     * Test Classification List
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', '/course');
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testCreate()
    {
        $response = $this->call('GET', '/course/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCopy()
    {
        $course = $this->createCourse();
        $response = $this->call('GET', '/course/'.$course->id.'/copy');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEdit()
    {
        $course = $this->createCourse();
        $response = $this->call('GET', '/course/'.$course->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteCourse()
    {
        $course = $this->createCourse();
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
            ['_token' => csrf_token(), 'course_ids' => [ $this->faker->userName ] ]);
        $response->assertSessionHasErrors( 'course_ids.0' );

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

    function testRequiredName()
    {
        $this->validateFields([ 'name' => null])->assertSessionHasErrors('name');
    }

    function testInvalidName()
    {
        $this->validateFields([ 'name' => Str::random(65)])->assertSessionHasErrors('name');
    }

    function testRequiredWebReception()
    {
        $this->validateFields([ 'web_reception' => null])->assertSessionHasErrors('web_reception');
    }

    function testInvalidWebReception()
    {
        $this->validateFields(['web_reception' => -1])->assertSessionHasErrors('web_reception');
    }

    function testInvalidCalendarId()
    {
        $this->validateFields([ 'calendar_id' => -1 ])->assertSessionHasErrors('calendar_id');
    }

    function testRequiredReceptionStartDay()
    {
        $this->validateFields([ 'reception_start_day' => null ])->assertSessionHasErrors('reception_start_day');
    }

    function testInvalidReceptionStartDay()
    {
        $this->validateFields([ 'reception_start_day' => $this->faker->userName ])->assertSessionHasErrors('reception_start_day');
    }

    function testRequiredReceptionStartMonth()
    {
        $this->validateFields([ 'reception_start_month' => null ])->assertSessionHasErrors('reception_start_month');
    }

    function testInvalidReceptionStartMonth()
    {
        $this->validateFields([ 'reception_start_month' => $this->faker->userName ])->assertSessionHasErrors('reception_start_month');
    }

    function testRequiredReceptionEndDay()
    {
        $this->validateFields([ 'reception_end_day' => null ])->assertSessionHasErrors('reception_end_day');
    }

    function testInvalidReceptionEndDay()
    {
        $this->validateFields([ 'reception_end_day' => $this->faker->userName ])->assertSessionHasErrors('reception_end_day');
    }

    function testRequiredReceptionEndMonth()
    {
        $this->validateFields([ 'reception_end_month' => null ])->assertSessionHasErrors('reception_end_month');
    }

    function testInvalidReceptionEndMonth()
    {
        $this->validateFields([ 'reception_end_month' => $this->faker->userName ])->assertSessionHasErrors('reception_end_month');
    }

    function testRequiredCancellationDeadline()
    {
        $this->validateFields([ 'cancellation_deadline' => null ])->assertSessionHasErrors('cancellation_deadline');
    }

    function testInvalidCancellationDeadline()
    {
        $this->validateFields([ 'cancellation_deadline' => $this->faker->userName ])->assertSessionHasErrors('cancellation_deadline');
    }

    function testInvalidIsPrice()
    {
        $this->validateFields([ 'is_price' => -1 ])->assertSessionHasErrors('is_price');
    }

    function testInvalidIsPriceMemo()
    {
        $this->validateFields(['is_price_memo' => -1 ])->assertSessionHasErrors('is_price_memo');
    }

    function testRequiredPrice()
    {
        $this->validateFields([ 'is_price' => 1, 'price' => null ])->assertSessionHasErrors('price');
    }

    function testInvalidPrice()
    {
        $this->validateFields([ 'is_price' => 1, 'price' => $this->faker->word ])->assertSessionHasErrors('price');
    }

    function testRequiredPriceMemo()
    {
        $this->validateFields([ 'is_price_memo' => 1, 'price_memo' => null ])->assertSessionHasErrors('price_memo');
    }

    function testInvalidTaxClass()
    {
        $this->validateFields([ 'tax_class' => -1 ])->assertSessionHasErrors('tax_class');
    }

    function testRequiredIsPreAccount()
    {
        $this->validateFields([ 'is_pre_account' => null ])->assertSessionHasErrors('is_pre_account');
    }

    function testInvalidIsPreAccount()
    {
        $this->validateFields([ 'is_pre_account' => -1 ])->assertSessionHasErrors('is_pre_account');
    }

    function testInvalidCourseOption()
    {
        $this->validateFields([ 'course_option_ids' => $this->faker->userName ])->assertSessionHasErrors('course_option_ids');
    }

    function testInvalidCourseOptionId()
    {
        $this->validateFields([ 'course_option_ids' => [ $this->faker->userName ] ])->assertSessionHasErrors('course_option_ids.0');
    }

    function testInvalidMinorIds()
    {
        $this->validateFields([ 'minor_ids' => $this->faker->userName ])->assertSessionHasErrors('minor_ids');
    }

    function testInvalidMinorValues()
    {
        $this->validateFields([ 'minor_values' => $this->faker->userName ])->assertSessionHasErrors('minor_values');
    }

    function testInvalidIsQuestions()
    {
        $this->validateFields([ 'is_questions' => $this->faker->userName ])->assertSessionHasErrors('is_questions');
    }

    function testInvalidIsQuestionsValue()
    {
        $this->validateFields([ 'is_questions' => [ -1, 1, 0, 0, 1 ] ])->assertSessionHasErrors('is_questions.0');
    }

    function testInvalidQuestionTitles()
    {
        $this->validateFields([ 'question_titles' => $this->faker->userName ])->assertSessionHasErrors('question_titles');
    }

    function testInvalidAnswer01s()
    {
        $this->validateFields([ 'answer01s' => $this->faker->userName ])->assertSessionHasErrors('answer01s');
    }

    function testInvalidAnswer02s()
    {
        $this->validateFields([ 'answer02s' => $this->faker->userName ])->assertSessionHasErrors('answer02s');
    }

    function testInvalidAnswer03s()
    {
        $this->validateFields([ 'answer03s' => $this->faker->userName ])->assertSessionHasErrors('answer03s');
    }


    function testInvalidAnswer04s()
    {
        $this->validateFields([ 'answer04s' => $this->faker->userName ])->assertSessionHasErrors('answer04s');
    }

    function testInvalidAnswer05s()
    {
        $this->validateFields([ 'answer05s' => $this->faker->userName ])->assertSessionHasErrors('answer05s');
    }

    function testInvalidAnswer06s()
    {
        $this->validateFields([ 'answer06s' => $this->faker->userName ])->assertSessionHasErrors('answer06s');
    }

    function testInvalidAnswer07s()
    {
        $this->validateFields([ 'answer07s' => $this->faker->userName ])->assertSessionHasErrors('answer07s');
    }

    function testInvalidAnswer08s()
    {
        $this->validateFields([ 'answer08s' => $this->faker->userName ])->assertSessionHasErrors('answer08s');
    }

    function testInvalidAnswer09s()
    {
        $this->validateFields([ 'answer09s' => $this->faker->userName ])->assertSessionHasErrors('answer09s');
    }

    function testInvalidAnswer10s()
    {
        $this->validateFields([ 'answer10s' => $this->faker->userName ])->assertSessionHasErrors('answer10s');
    }

    /**
     * validate fields process
     *
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function validateFields($attributes)
    {
        $this->withExceptionHandling();
        return $this->post('/course', $this->validFields($attributes));
    }

    function testCreateCourse()
    {
        $response = $this->call('POST', 'course', $this->validFields());
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testUpdateCourse() {
        $course = $this->createCourse();

        $response = $this->put( "/course/{$course->id}", $this->validFields());
        $this->assertEquals( 302, $response->getStatusCode() );

    }

    /**
     * Course fields
     *
     * @param $overwrites
     *
     * @return array
     */
    protected function validFields($overwrites = [])
    {
        $hospital = factory(Hospital::class)->create();
        $calendar = factory(Calendar::class)->create([ 'hospital_id' => $hospital->id ]);
        $tax_class = factory(TaxClass::class)->create();
        $images = factory(HospitalImage::class, 2)->create([ 'hospital_id' => $hospital->id ]);
        $image_orders = factory(ImageOrder::class, 2)->create();
        $image_ids = $images->map(function($img){ return $img->id; })->toArray();
        $image_order_ids = $image_orders->map(function($order){ return $order->id; })->toArray();
        $options = factory(Option::class, 2)->create([ 'hospital_id' => $hospital->id ]);
        $option_ids = $options->map(function($option){ return $option->id; })->toArray();
        $minors = factory(MinorClassification::class, 'with_major_middle', 5)->create();
        $minor_ids = $minors->map(function($minor){ return $minor->id; })->toArray();
        $minor_values = $minors->map(function($minor) {
            return $minor->is_fregist == '1' ? $minor->id : $this->faker->text;
        })->toArray();

        $fields = factory(Course::class)->raw([
            'calendar_id' => $calendar->id,
            'tax_class' => $tax_class->id,
            'course_images' => $image_ids,
            'course_image_orders' => $image_order_ids,
            'reception_start_day' => $this->faker->randomElement(range(1, 31)),
            'reception_start_month' => $this->faker->randomElement(range(1, 12)),
            'reception_end_day' => $this->faker->randomElement(range(1, 31)),
            'reception_end_month' => $this->faker->randomElement(range(1, 12)),
            'option_ids' => $option_ids,
            'is_questions' => [1, 1, 1, 1, 1],
            'question_titles' => $this->faker->words(5),
            'answer01s' => $this->faker->words(5),
            'answer02s' => $this->faker->words(5),
            'answer03s' => $this->faker->words(5),
            'answer04s' => $this->faker->words(5),
            'answer05s' => $this->faker->words(5),
            'answer06s' => $this->faker->words(5),
            'answer07s' => $this->faker->words(5),
            'answer08s' => $this->faker->words(5),
            'answer09s' => $this->faker->words(5),
            'answer10s' => $this->faker->words(5),
            'minor_ids' => $minor_ids,
            'minor_values' => $minor_values
        ]);
        $fields['_token'] = csrf_token();
        return array_merge($fields, $overwrites);
    }
}
