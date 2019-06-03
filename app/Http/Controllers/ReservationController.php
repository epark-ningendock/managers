<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Hospital;
use App\Customer;
use App\Course;
//use App\CourseOption;
//use App\Option;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservation;
    protected $hospital;
    protected $customer;
    protected $course;

    public function __construct(
        Reservation $reservation,
        Hospital $hospital,
        Customer $customer,
        Course $course
    ) {
        $this->reservation = $reservation;
        $this->hospital = $hospital;
        $this->customer = $customer;
        $this->course = $course;
    }
    /**
     * 一覧表示.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $query = $this->reservation
            ->byRequest($request)
            ->with(['hospital', 'course', 'customer'])
            ->orderBy('created_at', 'desc');

        $reservations = $query->paginate(env('PAGINATE_NUMBER'));

        return view('reservation.index', compact('reservations', 'params'));
    }
}
