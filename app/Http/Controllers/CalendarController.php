<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        //
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
        //
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
