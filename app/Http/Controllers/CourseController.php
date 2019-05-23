<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseDetail;
use App\CourseImage;
use App\CourseOption;
use App\CourseQuestion;
use App\HospitalImage;
use App\Http\Requests\CourseFormRequest;
use App\MajorClassification;
use App\MinorClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Option;
use App\ImageOrder;
use App\TaxClass;
use App\Calendar;
use Mockery\Exception;

class CourseController extends Controller
{
    /**
     * Display a listing of the course.
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     */
    public function index()
    {
        $courses = Course::orderBy('order')->paginate(10);
        return view('course.index', ['courses' => $courses]);
    }

    /**
     * * Show the form for creating a new resource.
     * @return mixed
     */
    public function create()
    {
        $images = HospitalImage::all();
        $majors = MajorClassification::orderBy('order')->get();
        $options = Option::orderBy('order')->get();
        $image_orders = ImageOrder::orderBy('order')->get();
        $tax_classes = TaxClass::all();
        $calendars = Calendar::all();

        return view('course.create')
            ->with('calendars', $calendars)
            ->with('tax_classes', $tax_classes)
            ->with('image_orders', $image_orders)
            ->with('options', $options)
            ->with('majors', $majors)
            ->with('images', $images);
    }

    /**
     * course copy
     * @param $id
     * @return mixed
     */
    public function copy($id)
    {
        $courses = Course::findOrFail($id);
        return $this->create()->with('course', $courses);
    }

    /**
     * Store a newly created resource in storage.
     * @param CourseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(CourseFormRequest $request)
    {
        try {
            $this->saveCourse($request, null);
            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.course')]));
            return redirect('course');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $images = HospitalImage::all();
        $majors = MajorClassification::orderBy('order')->get();
        $options = Option::orderBy('order')->get();
        $image_orders = ImageOrder::orderBy('order')->get();
        $tax_classes = TaxClass::all();
        $calendars = Calendar::all();

        return view('course.edit')
            ->with('calendars', $calendars)
            ->with('tax_classes', $tax_classes)
            ->with('image_orders', $image_orders)
            ->with('options', $options)
            ->with('majors', $majors)
            ->with('images', $images)
            ->with('course', $course);
    }

    protected function saveCourse(CourseFormRequest $request, $course_param)
    {
        try {
            DB::beginTransaction();

            //Course
            $course_data = $request->only([
                'name',
                'web_reception',
                'calendar_id',
                'is_category',
                'course_point',
                'course_notice',
                'course_cancel',
                'cancellation_deadline',
                'is_price',
                'price',
                'is_price_memo',
                'price_memo',
                'tax_class',
                'is_pre_account_price'
            ]);
            $reception_start_day = $request->input('reception_start_day');
            $reception_start_month = $request->input('reception_start_month');
            $reception_end_day = $request->input('reception_end_day');
            $reception_end_month = $request->input('reception_end_month');
            $reception_acceptance_day = $request->input('reception_acceptance_day');
            $reception_acceptance_month = $request->input('reception_acceptance_month');
            $course_data['reception_start_date'] = $reception_start_month * 1000 + $reception_start_day;
            $course_data['reception_end_date'] = $reception_end_month * 1000 + $reception_end_day;
            $course_data['reception_acceptance_date'] = $reception_acceptance_month * 1000 + $reception_acceptance_day;
            $course_data['course_cancel'] = '0';
            $course_data['order'] = 0;

            if (isset($course_param)) {
                $course = $course_param;
            } else {
                $course = new Course();
            }
            $course->fill($course_data);
            $course->save();

            //Course Image
            $image_ids = collect($request->input('course_images'));
            $image_order_ids = collect($request->input('course_image_orders'));

            $filtered_image_ids = $image_ids->filter(function($id) {
                return $id != 0;
            });

            $images = HospitalImage::whereIn('id', $filtered_image_ids)->get();
            if ($images->count() != count($filtered_image_ids)) {
                $request->session()->flash('error', trans('messages.invalid_hospital_image_id'));
                return redirect()->back();
            }

            if (isset($course_param)) {
                $course->course_images()->forceDelete();
            }

            foreach ($image_ids as $index => $image_id) {
                if ($image_id == 0) continue;
                $image_order_id = $image_order_ids[$index];
                $course_image = new CourseImage();
                $course_image->course_id = $course->id;
                $course_image->hospital_image_id = $image_id;
                $course_image->image_order_id = $image_order_id;
                $course_image->save();
            }


            //Course Options
            $option_ids = collect($request->input('option_ids', []));

            if ($option_ids->isNotEmpty()) {
                $options = Option::whereIn('id', $option_ids)->get();
                if ($options->count() != $option_ids->count()) {
                    $request->session()->flash('error', trans('messages.invalid_option_id'));
                    return redirect()->back();
                }

                if (isset($course_param)) {
                    $course->course_options()->forceDelete();
                }

                foreach ($option_ids as $option_id) {
                    $course_option = new CourseOption();
                    $course_option->course_id = $course->id;
                    $course_option->option_id = $option_id;
                    $course_option->save();
                }
            }

            //Course Detail
            $minor_ids = collect($request->input('minor_ids'), []);
            $minor_values = collect($request->input('minor_values'), []);

            if($minor_ids->isNotEmpty()) {
                $minors = MinorClassification::whereIn('id', $minor_ids)->orderBy('order')->get();
                if ($minors->count() != count($minor_ids)) {
                    $request->session()->flash('error', trans('messages.invalid_minor_id'));
                    return redirect()->back();
                }

                if (isset($course_param)) {
                    $course->course_details()->forceDelete();
                }
                foreach($minors as $index => $minor) {
                    $input_index = $minor_ids->search(function($id) use ($minor) {
                        return $minor->id == $id;
                    });

                    if ($input_index == -1 || ($minor->is_fregist == '1' && $minor_values[$input_index] == 0)
                        || ($minor->is_fregist == '0' && $minor_values[$input_index] == '')) continue;


                    $course_detail = new CourseDetail();
                    $course_detail->course_id = $course->id;
                    $course_detail->minor_classification_id = $minor->id;
                    $course_detail->major_classification_id = $minor->major_classification_id;
                    $course_detail->middle_classification_id = $minor->middle_classification_id;
                    if ($minor->is_fregist == '1') {
                        $course_detail->select_status = 1;
                    } else {
                        $course_detail->inputstring = $minor_values[$input_index];
                    }
                    $course_detail->save();
                }
            }

            //Course Question
            $is_questions = $request->input('is_questions');
            $question_titles = $request->input('question_titles');
            $answer01s = $request->input('answer01s');
            $answer02s = $request->input('answer02s');
            $answer03s = $request->input('answer03s');
            $answer04s = $request->input('answer04s');
            $answer05s = $request->input('answer05s');
            $answer06s = $request->input('answer06s');
            $answer07s = $request->input('answer07s');
            $answer08s = $request->input('answer08s');
            $answer09s = $request->input('answer09s');
            $answer10s = $request->input('answer10s');

            if (isset($course_param)) {
                $course->course_questions()->forceDelete();
            }
            for($i =  0; $i < 5; $i++) {
                $course_question = new CourseQuestion();
                $course_question->question_number = $i + 1;
                $course_question->course_id = $course->id;
                $course_question->is_question = $is_questions[$i];
                $course_question->question_title = $question_titles[$i];
                $course_question->answer01 = $answer01s[$i];
                $course_question->answer02 = $answer02s[$i];
                $course_question->answer03 = $answer03s[$i];
                $course_question->answer04 = $answer04s[$i];
                $course_question->answer05 = $answer05s[$i];
                $course_question->answer06 = $answer06s[$i];
                $course_question->answer07 = $answer07s[$i];
                $course_question->answer08 = $answer08s[$i];
                $course_question->answer09 = $answer09s[$i];
                $course_question->answer10 = $answer10s[$i];
                $course_question->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseFormRequest $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseFormRequest $request, Course $course)
    {
        try {
            $this->saveCourse($request, $course);
            $request->session()->flash('success', trans('messages.updated', ['name' => trans('messages.names.course')]));
            return redirect('course');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.update_error'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Request $request)
    {
        $course->course_details()->delete();
        $course->course_options()->delete();
        $course->course_questions()->delete();
        $course->course_images()->delete();
        $course->delete();
        $request->session()->flash('success', trans('messages.deleted', ['name' => trans('messages.names.course')]));
        return redirect()->back();
    }

    /**
     * Course sort
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sort()
    {
        $courses = Course::orderBy('order')->get();
        return view('course.sort')->with('courses', $courses);
    }

    /**
     * update course order
     * @param CourseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSort(CourseFormRequest $request)
    {
        $ids = $request->input('course_ids');
        $courses = Course::whereIn('id', $ids)->get();

        if (count($ids) != $courses->count()) {
            $request->session()->flash('error', trans('messages.invalid_course_id'));
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            foreach ($courses as $course) {
                $index = array_search($course->id, $ids, false);
                $course->order = $index + 1;
                $course->save();
            }
            DB::commit();
            $request->session()->flash('success', trans('messages.course_sort_updated'));
            return redirect('course');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', trans('messages.create_error'));
            return redirect()->back();
        }
    }
}