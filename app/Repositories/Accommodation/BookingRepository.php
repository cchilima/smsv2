<?php

namespace App\Repositories\Accommodation;

use App\Models\Accomodation\Booking;
use Carbon\Carbon;

class BookingRepository
{

    public function create($data)
    {
        return Booking::create($data);
    }

    public function getAll()
    {
        return Booking::orderBy()->get();
    }

    public function update($id, $data)
    {
        return Booking::find($id)->update($data);
    }

    public function find($id)
    {
        return Booking::find($id);
    }
    public function getOpenBookings()
    {
       // return Booking::with('student','bedSpace')->get();
        $currentDateTime = Carbon::now();
        return Booking::with('student','bedSpace')->whereHas('bedSpace', function ($query) use ($currentDateTime) {
            $query->where('is_available','=','true');
        })->get();
    }
    public function getClosedBookings()
    {
        $currentDateTime = Carbon::now();
        return Booking::with('student.user','bedSpace.room.hostel')->whereHas('bedSpace', function ($query) use ($currentDateTime) {
            $query->where('is_available','=','false')->whereDate('expiration_date', '>=', $currentDateTime);
        })->get();
    }
    public function getClosedBookingsOne($student_id)
    {
        $currentDateTime = Carbon::now();
        return Booking::with('student.user','bedSpace.room.hostel')->where('student_id','=',$student_id)->whereHas('bedSpace', function ($query) use ($currentDateTime) {
            $query->where('is_available','=','false')->whereDate('expiration_date', '>=', $currentDateTime);
        })->get();
    }

}
