<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\CalendarDay;
use App\Holiday;
use App\Hospital;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Course;
use App\Http\Requests\CalendarFormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Reservation;
use Yasumi\Yasumi;
use Reshadman\OptimisticLocking\StaleModelLockingException;
use Illuminate\Support\Facades\Mail;
use App\Mail\Calander\CalendarSettingNotificationMail;
use Illuminate\Support\Facades\Auth;
use App\Enums\CalendarDisplay;

class CalendarController extends Controller
{
    const EPARK_MAIL_ADDRESS = "dock_all@eparkdock.com";

    /**
     * Display a listing of the calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calendars = Calendar::with('courses')->where('hospital_id', session()->get('hospital_id'))->get();
        return view('calendar.index', ['calendars' => $calendars]);
    }

    /**
     * Show the form for creating a new calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unregistered_courses = Course::whereNull('calendar_id')->where('hospital_id', session()->get('hospital_id'))->get();
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
            $calendar = $this->saveCalendar($request, null);

            $data = [
                'calendar' => $calendar,
                'staff_name' => Auth::user()->name,
                'subject' => '【EPARK人間ドック】カレンダー登録・更新・削除のお知らせ',
                'processing' => '登録'
             ];
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new CalendarSettingNotificationMail($data));

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
        $unregistered_courses = Course::whereNull('calendar_id')->where('hospital_id', session()->get('hospital_id'))->get();
        return view('calendar.edit')
            ->with('unregistered_courses', $unregistered_courses)
            ->with('calendar', $calendar);
    }

    protected function saveCalendar($request, $calendar)
    {
        try {
            DB::beginTransaction();
            $calendar_data = $request->only(['name', 'is_calendar_display', 'lock_version']);
            if (!isset($calendar)) {
                $calendar = new Calendar($calendar_data);
                $calendar->hospital_id = session()->get('hospital_id');
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
            return $calendar;
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

            $data = [
                'calendar' => $calendar,
                'staff_name' => Auth::user()->name,
                'subject' => '【EPARK人間ドック】カレンダー登録・更新・削除のお知らせ',
                'processing' => '更新'
             ];
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new CalendarSettingNotificationMail($data));

            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.calendar')]));
            return redirect('calendar');
        } catch (StaleModelLockingException $e) {
            Session::flash('error', trans('messages.model_changed_error'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', trans('messages.update_error'));
            return redirect('calendar');
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
        try {
            $calendar = Calendar::findOrFail($calendar->id);
            $calendar->delete();
    
            $data = [
                'calendar' => $calendar,
                'staff_name' => Auth::user()->name,
                'subject' => '【EPARK人間ドック】カレンダー登録・更新・削除のお知らせ',
                'processing' => '削除'
             ];
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new CalendarSettingNotificationMail($data));

            return redirect('calendar')->with('error', trans('messages.deleted', ['name' => trans('messages.names.calendar')]));
        } catch (StaleModelLockingException $e) {
            Session::flash('error', trans('messages.model_changed_error'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', trans('messages.update_error'));
            return redirect('calendar');
        }
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

        $public_holidays = collect(Yasumi::create('Japan', $start->year, 'ja_JP')->getHolidays())->flatten(1);

        if ($start->year != $end->year) {
            $temp = collect(Yasumi::create('Japan', $end->year, 'ja_JP')->getHolidays())->flatten(1);
            $public_holidays = $public_holidays->merge($temp);
        }

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

            $p_holiday = $public_holidays->first(function ($h) use ($start) {
                return $start->isSameDay($h);
            });

            $reservation = $reservation_counts->get($start->format('Ymd'));
            $is_holiday = isset($holiday) ? $holiday->is_holiday : 0;
            $month->push([ 'date' => $start->copy(), 'is_holiday' => $is_holiday, 'holiday' =>  $p_holiday, 'calendar_day' => $calendar_day, 'reservation_count' => $reservation ]);

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
            // force to update calendar lock version
            $calendar->lock_version = $request->input('lock_version');
            $calendar->touch();
            $calendar->save();

            $start = Carbon::parse($request->input('days')[0]);
            $end = Carbon::now()->addMonth(5)->endOfMonth();

            $calendar_days = CalendarDay::where('calendar_id', $id)
                ->whereDate('date', '>=', $start->toDateString())
                ->whereDate('date', '<=', $end->toDateString())->get();

            $days = collect($request->input('days'));
            $is_reservation_acceptances = collect($request->input('is_reservation_acceptances'));
            $reservation_frames = collect($request->input('reservation_frames'));

            while ($start->lt($end)) {
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

            $data = [
                'calendar' => $calendar,
                'staff_name' => Auth::user()->name,
                'subject' => '【EPARK人間ドック】カレンダー登録・更新・削除のお知らせ',
                'processing' => 'カレンダー設定の更新'
             ];
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new CalendarSettingNotificationMail($data));

            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.calendar_setting')]));
            DB::commit();
            return redirect('calendar');
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            Session::flash('error', trans('messages.model_changed_error'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', trans('messages.update_error'));
            return redirect()->back()->withInput();
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

        $holidays = Holiday::where('hospital_id', session()->get('hospital_id'))->whereDate('date', '>=', $start->toDateString())
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

            $is_holiday = isset($holiday) && $holiday->is_holiday == 1;
            $lock_version = isset($holiday) ? $holiday->lock_version : '';
            $month->push([ 'date' => $start->copy(), 'is_holiday' => $is_holiday , 'holiday' =>  $p_holiday, 'lock_version' => $lock_version]);

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

            $hospital_id = session()->get('hospital_id');
            $holidays = Holiday::where('hospital_id', $hospital_id)->whereDate('date', '>=', $start->toDateString())
                ->whereDate('date', '<=', $end->toDateString())->get();

            $days = collect($request->input('days'));
            $is_holidays = collect($request->input('is_holidays'));
            $lock_versions = collect($request->input('lock_versions'));

            $new_holidays = collect();

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

                $index = $days->search(function ($d) use ($start) {
                    return $start->format('Ymd') == $d;
                });

                $is_holiday = $is_holidays->get($index);
                $lock_version = $lock_versions->get($index);

                $holiday = $holidays->first(function ($day) use ($start) {
                    return $day->date->isSameDay($start);
                });

                $p_holiday = $public_holidays->first(function ($h) use ($start) {
                    return $start->isSameDay($h);
                });


                if (!isset($holiday) && ($is_holiday == 1 || isset($p_holiday))) {
                    $new_holidays->push([
                        'hospital_id' => $hospital_id,
                        'date' => $start->copy(),
                        'is_holiday' => $is_holiday,
                        'created_at' => Carbon::now(),
                        'updated_at'=> Carbon::now()
                    ]);
                } elseif (isset($holiday)) {
                    if (!isset($p_holiday) && $is_holiday == 0) {
                        $holiday->forceDelete();
                    } else {
                        $holiday->lock_version = $lock_version;
                        $holiday->is_holiday = $is_holiday;
                        $holiday->save();
                    }
                }
                $start->addDay(1);
            }

            Holiday::insert($new_holidays->toArray());
            $hospital = Hospital::findOrFail(1);
            
            $data = [
                'hospital' => $hospital,
                'staff_name' => Auth::user()->name,
                'subject' => '【EPARK人間ドック】休日設定更新のお知らせ',
                'processing' => '更新'
             ];
            Mail::to(self::EPARK_MAIL_ADDRESS)->send(new CalendarSettingNotificationMail($data));

            Session::flash('success', trans('messages.updated', ['name' => trans('messages.names.holiday_setting')]));
            DB::commit();
            return redirect('calendar');
        } catch (StaleModelLockingException $e) {
            DB::rollback();
            Session::flash('error', trans('messages.model_changed_error'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', trans('messages.update_error'));
            return redirect()->back();
        }
    }

    public function reservationDays($course_id, Request $request)
    {
        $calendars = [];
        $course = Course::find($course_id);
        if ($course) {
            $today = Carbon::parse($request->input('start_date', Carbon::today()->format('Y/m/d')));
            $started_date = (Carbon::MONDAY == $today->dayOfWeek) ? $today : $today->previous(Carbon::MONDAY);

            if(isset($request->reservation_date)) {
                $reservation_date = Carbon::parse($request->reservation_date);
                if ($reservation_date->isPast()) {
                    $started_date = $reservation_date->copy()->previous(Carbon::MONDAY);
                }
            }

            // 16 weeks range
            $end_date = $started_date->copy()->addDay(111);



            $calendar_days = $course->calendar->calendar_days()
                ->whereBetween('date', [$started_date, $end_date])->orderBy('date')->get();

            $holidays = Holiday::where('hospital_id', session()->get('hospital_id'))
                                ->where('is_holiday', 1)
                                ->whereBetween('date', [$started_date, $end_date])
                                ->orderBy('date')->get();


            $period = CarbonPeriod::create($started_date, $end_date);
            $dates = $period->toArray();
            $calendars = collect();
            
            foreach ($dates as $date) {
                $calendar_day = $calendar_days->first(function ($day) use ($date) {
                    return $day->date->isSameDay($date);
                });

                $holiday = $holidays->first(function ($day) use ($date) {
                    return $day->date->isSameDay($date);
                });

                $calendars->push([
                    'date' => $date,
                    'is_holiday' => isset($holiday),
                    'frame' => isset($calendar_day)? $calendar_day->reservation_frames : -1,
                    'is_reservation_acceptance' => (!isset($calendar_day) || $calendar_day->is_reservation_acceptance == CalendarDisplay::SHOW)
                ]);
            }
        }


        return response()->json([
            'data' => view('calendar.partials.daybox', [
                'calendars' => $calendars
            ])->render(),
        ]);
    }


    public function showCalendarGenerator()
    {

        $end_date = 1000;

        $period = CarbonPeriod::create(Carbon::now()->addDay(0)->format('Y-m-d'), Carbon::now()->addDay($end_date)->format('Y-m-d'));
        $dates = $period->toArray();
        $calendars = [];

        foreach ($dates as $date) {
            $calendars[] = factory(CalendarDay::class)->make([
                'date' => $date->format('Y-m-d'),
            ]);
        }
        return $this->paginateWithoutKey(collect($calendars), 7, request('page'));

    }
}
