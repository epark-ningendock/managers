<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Course;
use App\Http\Requests\CalendarFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CalendarController extends Controller
{
    /**
     * Display a listing of the calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calendars = Calendar::with('courses')->get();
        return view('calendar.index', ['calendars' => $calendars]);
    }

    /**
     * Show the form for creating a new calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unregistered_courses = Course::whereNull('calendar_id')->get();
        return view('calendar.create', ['unregistered_courses' => $unregistered_courses ]);
    }

    /**
     * Store a newly created Calendar.
     * @param CalendarFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarFormRequest $request)
    {
        try {
            $this->saveCalendar($request, null);
            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.calendar')]));
            return redirect('calendar');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        $unregistered_courses = Course::whereNull('calendar_id')->get();
        return view('calendar.edit')
            ->with('unregistered_courses', $unregistered_courses)
            ->with('calendar', $calendar);
    }

    protected function saveCalendar($request, $calendar)
    {
        try {
            DB::beginTransaction();
            $calendar_data = $request->only(['name', 'is_calendar_display']);
            if (!isset($calendar)) {
                $calendar = new Calendar($calendar_data);
            } else {
                $calendar->fill($calendar_data);
            }
            $calendar->save();

            $unregistered_course_ids = $request->input('unregistered_course_ids');
            if (isset($unregistered_course_ids) && count($unregistered_course_ids) > 0) {
                Course::whereIn('id', $unregistered_course_ids)->update([ 'calendar_id' => null ]);
            }


            $registered_course_ids = $request->input('registered_course_ids');
            if (isset($registered_course_ids) && count($registered_course_ids) > 0) {
                Course::whereIn('id', $registered_course_ids)->update([ 'calendar_id' => $calendar->id ]);
            }

            Session::flash('success', trans('messages.created', ['name' => trans('messages.names.calendar')]));
            DB::commit();
            return redirect('calendar');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        try {
            $this->saveCalendar($request, $calendar);
            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.calendar')]));
            return redirect('calendar');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(trans('messages.update_error'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {

    }

    /**
     * Display calendar setting
     * @param Calendar $calendar
     * @return \Illuminate\Http\Response
     */
    public function setting(Calendar $calendar)
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonth(5)->endOfMonth();
        $months = collect();

        while($start->gt($end)) {
            $key = $start->format('%y年%M月');
            $month = $months->get($key);

            if (!isset($month)) {
                $month = collect();
                $months->put($key, $month);
            }

            $month->push($start->copy());
            $start->addDay(1);
        }

        return view('calendar.setting')
            ->with('months', $months)
            ->with('start', $start)
            ->with('end', $end);
    }

    /**
     * Update calendar setting
     * @param Calendar $calendar
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(Calendar $calendar)
    {

    }
}
