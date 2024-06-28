<?php

namespace App\Repositories\Accommodation;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Accomodation\BedSpace;
use App\Models\Accomodation\Booking;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use Carbon\Carbon;

class BedSpaceRepository
{
    public function create($data)
    {
        return BedSpace::create($data);
    }

    public function getAll()
    {
        return BedSpace::with('room')->get();
    }
    public function capacity($room_id)
    {
        return BedSpace::where('room_id',$room_id)->count();
    }
    public function getAvailable($id)
    {
        return BedSpace::with('room.hostel')->where('room_id',$id)->where('is_available','=','true')->get();
    }

    public function update($id, $data)
    {
        return BedSpace::find($id)->update($data);
    }

    public function find($id)
    {
        return BedSpace::find($id);
    }
    public function getActiveStudents($gender = null){
         $ac = AcademicPeriod::whereDate('ac_end_date', '>=', now())
            ->whereHas('academic_period_information', function ($query) {
                $query->where('study_mode_id', '=', 1);
            }) // If you still need to eager load the relationship
            ->distinct('id')
             ->pluck('id');
         $class = AcademicPeriodClass::whereIn('academic_period_id',$ac)->distinct('id')
             ->pluck('id');
/*
        // Get the student IDs based on academic period class IDs
        $studentIdsFromEnrollment = Enrollment::whereIn('academic_period_class_id', $class)
            ->distinct('student_id')
            ->pluck('student_id');

        // Get the student IDs based on expired bookings
        $studentIdsFromBooking = Booking::whereDate('expiration_date', '<', now())
            ->whereIn('student_id', $studentIdsFromEnrollment)
            ->pluck('student_id');

        // Combine the two sets of student IDs
        $combinedStudentIds = $studentIdsFromEnrollment->merge($studentIdsFromBooking)->unique();

        // Get the students who meet either of the conditions
        return Student::with('user')
            ->whereIn('id', $combinedStudentIds)
            ->get();*/
        $currentDateTime = Carbon::now();

// Get the distinct student IDs from the enrollment
        $studentIdsFromEnrollment = Enrollment::whereIn('academic_period_class_id', $class)
            ->distinct('student_id')
            ->pluck('student_id');

// Get the student IDs based on expired bookings
        $studentIdsFromBooking = Booking::whereDate('expiration_date', '<', $currentDateTime)
            ->whereIn('student_id', $studentIdsFromEnrollment)
            ->pluck('student_id');

// Combine the two sets of student IDs
        $combinedStudentIds = $studentIdsFromEnrollment->merge($studentIdsFromBooking)->unique();

// Get the student IDs whose expiration date has not passed and are in the enrollment
        $activeStudentIds = Booking::whereDate('expiration_date', '>=', $currentDateTime)
            ->whereIn('student_id', $studentIdsFromEnrollment)
            ->pluck('student_id');

// Remove active student IDs from the combined array
        $finalStudentIds = $combinedStudentIds->diff($activeStudentIds);

        return Student::with('user')
            ->whereIn('id', $finalStudentIds)
            ->get();


    }
    public function getActiveStudentOne($student_id,$gender = null){
        $ac = AcademicPeriod::whereDate('ac_end_date', '>=', now())
            ->whereHas('academic_period_information', function ($query) {
                $query->where('study_mode_id', '=', 1);
            }) // If you still need to eager load the relationship
            ->distinct('id')
            ->pluck('id');
        $class = AcademicPeriodClass::whereIn('academic_period_id',$ac)->distinct('id')
            ->pluck('id');
        $currentDateTime = Carbon::now();

// Get the distinct student IDs from the enrollment
        $studentIdsFromEnrollment = Enrollment::whereIn('academic_period_class_id', $class)->where('student_id',$student_id)
            ->distinct('student_id')
            ->pluck('student_id');

// Get the student IDs based on expired bookings
        $studentIdsFromBooking = Booking::whereDate('expiration_date', '<', $currentDateTime)->where('student_id',$student_id)
            ->whereIn('student_id', $studentIdsFromEnrollment)
            ->pluck('student_id');
        //dd($student_id);

// Combine the two sets of student IDs
        $combinedStudentIds = $studentIdsFromEnrollment->merge($studentIdsFromBooking)->unique();

// Get the student IDs whose expiration date has not passed and are in the enrollment
        $activeStudentIds = Booking::whereDate('expiration_date', '>=', $currentDateTime)
            ->whereIn('student_id', $studentIdsFromEnrollment)->where('student_id',$student_id)
            ->pluck('student_id');

// Remove active student IDs from the combined array
        $finalStudentIds = $combinedStudentIds->diff($activeStudentIds);

        return Student::with('user')->where('id',$student_id)
            ->whereIn('id', $finalStudentIds)
            ->get();
    }
}
