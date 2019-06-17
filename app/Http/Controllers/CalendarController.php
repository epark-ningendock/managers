<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\CalendarDay;
use App\Holiday;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Course;
use App\Http\Requests\CalendarFormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Reservation;
use Yasumi\Yasumi;
use \DateTime;

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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function setting($id)
    {
        $calendar = Calendar::findOrFail($id);
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonth(5)->endOfMonth();
        $months = collect();

        $calendar_days = CalendarDay::where('calendar_id', $id)
            ->whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())->get();

        $holidays = Holiday::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())->get();

        $reservation_counts = Reservation::join('courses', 'courses.id', '=', 'reservations.course_id')
            ->whereDate('reservation_date', '>=', $start->toDateString())
            ->whereDate('reservation_date', '<=', $end->toDateString())
            ->where('courses.calendar_id', $id)
            ->groupBy('reservation_date')
            ->orderBy('reservation_date')
            ->selectRaw('count(*) as count, DATE_FORMAT(reservation_date, "%Y%m%d") as reservation_date')
            ->pluck('count', 'reservation_date');

        while ($start->lt($end)) {
            $key = $start->format('Y年m月');
            $month = $months->get($key);

            if (!isset($month)) {
                $month = collect();
                $months->put($key, $month);
            }

            if ($start->day == 1 && $start->dayOfWeek != 0) {
                for ($i = 0; $i < $start->dayOfWeek; $i++) {
                    $month->push(null);
                }
            }

            $calendar_day = $calendar_days->first(function ($day) use ($start) {
                return $day->date->isSameDay($start);
            });

            $holiday = $holidays->first(function ($day) use ($start) {
                return $day->date->isSameDay($start);
            });

            $reservation = $reservation_counts->get($start->format('Ymd'));

            $month->push([ 'date' => $start->copy(), 'is_holiday' => isset($holiday), 'calendar_day' => $calendar_day, 'reservation_count' => $reservation ]);

            if ($start->isLastOfMonth() && !$start->isSaturday()) {
                for ($i = $start->dayOfWeek; $i < 6; $i++) {
                    $month->push(null);
                }
            }

            $start->addDay(1);
        }

        $start = Carbon::now()->startOfMonth();

        return view('calendar.setting')
            ->with('calendar', $calendar)
            ->with('months', $months)
            ->with('start', $start)
            ->with('end', $end);
    }

    /**
     * Update calendar setting
     * @param $id
     * @param CalendarFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateSetting($id, CalendarFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $calendar = Calendar::findOrFail($id);
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->addMonth(5)->endOfMonth();

            $calendar_days = CalendarDay::where('calendar_id', $id)
                ->whereDate('date', '>=', $start->toDateString())
                ->whereDate('date', '<=', $end->toDateString())->get();

            $days = collect($request->input('days'));
            $is_reservation_acceptances = collect($request->input('is_reservation_acceptances'));
            $reservation_frames = collect($request->input('reservation_frames'));

            while ($start->lt($end)) {
                if ($start->isPast()) {
                    $start->addDay(1);
                    continue;
                }
                $calendar_day = $calendar_days->first(function ($day) use ($start) {
                    return $day->date->isSameDay($start);
                });

                $index = $days->search(function ($d) use ($start) {
                    return $start->format('Ymd') == $d;
                });
                $is_reservation_acceptance = $is_reservation_acceptances->get($index);
                $reservation_frame = $reservation_frames->get($index);

                if (!isset($calendar_day)) {
                    $calendar_day = new CalendarDay();
                    $calendar_day->date = $start->copy();
                    $calendar_day->is_holiday = 0;
                    $calendar_days->push($calendar_day);
                }

                $calendar_day->is_reservation_acceptance = $is_reservation_acceptance;
                $calendar_day->reservation_frames = $reservation_frame;

                $start->addDay(1);
            }

            $calendar->calendar_days()->saveMany($calendar_days);

            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.calendar_setting')]));
            DB::commit();
            return redirect('calendar');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.update_error'))->withInput();
        }
    }

    /**
     * Display holiday setting
     * @return \Illuminate\Http\Response
     */
    public function holiday_setting()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonth(11)->endOfMonth();
        $months = collect();

        $holidays = Holiday::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())->get();

        $public_holidays = collect(Yasumi::create('Japan', $start->year, 'ja_JP')->getHolidays())->flatten(1);

        if ($start->year != $end->year) {
            $temp = collect(Yasumi::create('Japan', $end->year, 'ja_JP')->getHolidays())->flatten(1);
            $public_holidays = $public_holidays->merge($temp);
        }

        while ($start->lt($end)) {
            $key = $start->format('Y年m月');
            $month = $months->get($key);

            if (!isset($month)) {
                $month = collect();
                $months->put($key, $month);
            }

            if ($start->day == 1 && $start->dayOfWeek != 0) {
                for ($i = 0; $i < $start->dayOfWeek; $i++) {
                    $month->push(null);
                }
            }

            $holiday = $holidays->first(function ($day) use ($start) {
                return $day->date->isSameDay($start);
            });

            $p_holiday = $public_holidays->first(function ($h) use ($start) {
                return $start->isSameDay($h);
            });
            $month->push([ 'date' => $start->copy(), 'is_holiday' => isset($p_holiday) || isset($holiday), 'holiday' =>  $p_holiday]);

            if ($start->isLastOfMonth() && !$start->isSaturday()) {
                for ($i = $start->dayOfWeek; $i < 6; $i++) {
                    $month->push(null);
                }
            }

            $start->addDay(1);
        }

        $start = Carbon::now()->startOfMonth();

        return view('calendar.holiday')
            ->with('months', $months)
            ->with('start', $start)
            ->with('end', $end);
    }

    /**
     * Update holiday setting
     * @param CalendarFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update_holiday(CalendarFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->addMonth(11)->endOfMonth();
            $months = collect();

            $holidays = Holiday::whereDate('date', '>=', $start->toDateString())
                ->whereDate('date', '<=', $end->toDateString())->get();

            $days = collect($request->input('days'));
            $is_holidays = collect($request->input('is_holidays'));

            $new_holidays = collect();

            while ($start->lt($end)) {
                $key = $start->format('Y年m月');
                $month = $months->get($key);

                if (!isset($month)) {
                    $month = collect();
                    $months->put($key, $month);
                }

                $index = $days->search(function ($d) use ($start) {
                    return $start->format('Ymd') == $d;
                });
                $is_holiday = $is_holidays->get($index);

                $holiday = $holidays->first(function ($day) use ($start) {
                    return $day->date->isSameDay($start);
                });

                if ($is_holiday && !isset($holiday)) {
                    //TODO to add hospital_id from logined user
                    $new_holidays->push([ 'date' => $start->copy(), 'created_at' => Carbon::now(), 'updated_at'=> Carbon::now() ]);
                } elseif (isset($holiday)) {
                    $holiday->forceDelete();
                }
                $start->addDay(1);
            }

            Holiday::insert($new_holidays->toArray());

            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.holiday_setting')]));
            DB::commit();
            return redirect('calendar');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.update_error'))->withInput();
        }
    }
}
