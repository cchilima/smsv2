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
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $booking_repository,$bed_space_repository,$rooms_repository,$hostel_repository,$invoiceRepo;
    public function __construct(BookingRepository $booking_repository, BedSpaceRepository $bed_space_repository,
                                RoomRepository $rooms_repository, HostelRepository $hostel_repository,
                                InvoiceRepository $invoiceRepo)
    {
        //$this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);

        $this->bed_space_repository = $bed_space_repository;
        $this->booking_repository = $booking_repository;
        $this->rooms_repository = $rooms_repository;
        $this->hostel_repository = $hostel_repository;
        $this->invoiceRepo = $invoiceRepo;

    }
    public function index()
    {
        $data['hostel'] = $this->hostel_repository->getAll();
        $data['open'] = $this->booking_repository->getOpenBookings();
        $data['closed'] = $this->booking_repository->getClosedBookings();
        //dd($data);
        return view('pages.booking.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Booking $request)
    {
        $data = $request->only(['student_id', 'bed_space_id']);
        $data['booking_date'] = date('Y-m-d', strtotime(now()));
        $data['expiration_date'] = date('Y-m-d', strtotime('+1 day', time()));
       // 'student_id','bed_space_id','booking_date','expiration_date'
        $dataB['is_available'] = 'false';
        $data = $this->booking_repository->create($data);
        $this->bed_space_repository->update($data['bed_space_id'],$dataB);
        $this->booking_repository->invoiceStudent($data['student_id']);
        if ($data) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError(__('msg.create_failed'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = $this->booking_repository->find($id);
        $data['hostel'] = $this->hostel_repository->getAll();
        return !is_null($booking) ? view('pages.booking.edit', $data,compact('booking'))
            : Qs::goWithDanger('pages.hostels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingUpdate $request, string $id)
    {
        $data = $request->only(['student_id', 'bed_space_id']);
        $data['booking_date'] = date('Y-m-d', strtotime(now()));
        $data['expiration_date'] = date('Y-m-d', strtotime('+1 day', time()));
        $dataF['is_available'] = 'false';
        $current = $this->booking_repository->find($id);
        $dataB['is_available'] = 'true';
        $this->bed_space_repository->update($current->bed_space_id,$dataB);
        $update = $this->booking_repository->update($id,$data);
        $this->bed_space_repository->update($data['bed_space_id'],$dataF);

        if ($update) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError(__('msg.create_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $current = $this->booking_repository->find($id);
        $dataB['is_available'] = 'true';
        $this->bed_space_repository->update($current->bed_space_id,$dataB);
        $this->booking_repository->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
    public function ConfirmBooking(Request $request)
    {
        $id = $request->input('id');
        $student_id = $request->input('student_id');
        $studentIdsFromEnrollment = Enrollment::where('student_id', $student_id)
            ->first();
        //dd($request);
        $ac = AcademicPeriodClass::where('id',$studentIdsFromEnrollment->academic_period_class_id)->first();
        // Get next academic period
        $aca = AcademicPeriod::find($ac->academic_period_id);
        $data['expiration_date'] = $aca->ac_end_date;
        $data = $this->booking_repository->update($id,$data);
        if ($data) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError();
        }
    }

    public
    function getRooms(string $id)
    {
        return $this->rooms_repository->getSpecificRooms($id);
    }
    public
    function getBedSpaces(string $id)
    {
        $data['students'] = $this->bed_space_repository->getActiveStudents();
        $data['spaces'] = $this->bed_space_repository->getAvailable($id);
        return $data;
    }
}
