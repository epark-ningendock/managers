<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\CourseFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
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
        $course->course_detail()->delete();
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
