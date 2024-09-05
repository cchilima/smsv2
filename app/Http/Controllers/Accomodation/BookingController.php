<?php

namespace App\Http\Controllers\Accomodation;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Middleware\Custom\TeamSAT;
use App\Http\Requests\Accomodation\Booking;
use App\Http\Requests\Accomodation\BookingUpdate;
use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Repositories\Accommodation\BedSpaceRepository;
use App\Repositories\Accommodation\BookingRepository;
use App\Repositories\Accommodation\HostelRepository;
use App\Repositories\Accommodation\RoomRepository;
use App\Repositories\Accounting\InvoiceRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $booking_repository, $bed_space_repository, $rooms_repository, $hostel_repository, $invoiceRepo;
    public function __construct(
        BookingRepository $booking_repository,
        BedSpaceRepository $bed_space_repository,
        RoomRepository $rooms_repository,
        HostelRepository $hostel_repository,
        InvoiceRepository $invoiceRepo
    ) {
        //$this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);

        $this->bed_space_repository = $bed_space_repository;
        $this->booking_repository = $booking_repository;
        $this->rooms_repository = $rooms_repository;
        $this->hostel_repository = $hostel_repository;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Booking $request)
    {
        try {
            $data = $request->only(['student_id', 'bed_space_id']);
            $data['booking_date'] = date('Y-m-d', strtotime(now()));
            $data['expiration_date'] = date('Y-m-d', strtotime('+1 day', time()));
            // 'student_id','bed_space_id','booking_date','expiration_date'
            $dataB['is_available'] = 'false';
            $data = $this->booking_repository->create($data);
            $this->bed_space_repository->update($data['bed_space_id'], $dataB);
            $this->booking_repository->invoiceStudent($data['student_id']);

            return Qs::jsonStoreOk('Booking created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create record: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = $this->booking_repository->find($id);
        $data['hostel'] = $this->hostel_repository->getAll();
        return !is_null($booking) ? view('pages.booking.edit', $data, compact('booking'))
            : Qs::goWithDanger('pages.hostels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingUpdate $request, string $id)
    {
        try {
            $data = $request->only(['student_id', 'bed_space_id']);
            $data['booking_date'] = date('Y-m-d', strtotime(now()));
            $data['expiration_date'] = date('Y-m-d', strtotime('+1 day', time()));
            $dataF['is_available'] = 'false';
            $current = $this->booking_repository->find($id);
            $dataB['is_available'] = 'true';
            $this->bed_space_repository->update($current->bed_space_id, $dataB);
            $update = $this->booking_repository->update($id, $data);
            $this->bed_space_repository->update($data['bed_space_id'], $dataF);

            return Qs::jsonStoreOk('Booking updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create booking: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $current = $this->booking_repository->find($id);
            $dataB['is_available'] = 'true';
            $this->bed_space_repository->update($current->bed_space_id, $dataB);
            $this->booking_repository->find($id)->delete();

            return Qs::goBackWithSuccess('Booking deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete booking referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete booking: ' . $th->getMessage());
        }
    }

    public function ConfirmBooking(Request $request)
    {
        try {
            $id = $request->input('id');
            $student_id = $request->input('student_id');
            $studentIdsFromEnrollment = Enrollment::where('student_id', $student_id)->first();
            $ac = AcademicPeriodClass::where('id', $studentIdsFromEnrollment->academic_period_class_id)->first();

            // Get next academic period
            $aca = AcademicPeriod::find($ac->academic_period_id);
            $data['expiration_date'] = $aca->ac_end_date;
            $data = $this->booking_repository->update($id, $data);

            return Qs::jsonStoreOk('Booking confirmed successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to confirm booking: ' . $th->getMessage());
        }
    }

    public function getRooms(string $id)
    {
        return $this->rooms_repository->getSpecificRooms($id);
    }

    public function getBedSpaces(string $id)
    {
        $data['students'] = $this->bed_space_repository->getActiveStudents();
        $data['spaces'] = $this->bed_space_repository->getAvailable($id);
        return $data;
    }
}
