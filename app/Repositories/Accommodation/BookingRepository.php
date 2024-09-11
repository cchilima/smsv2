<?php

namespace App\Repositories\Accommodation;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\AcademicPeriodFee;
use App\Models\Accomodation\Booking;
use App\Models\Accounting\Fee;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\InvoiceDetail;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\StatementRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingRepository
{
    protected $statementRepo;
    protected $registrationRepo, $booking_repository;

    public function __construct(StatementRepository $statementRepo, StudentRegistrationRepository $registrationRepo)
    {
        $this->statementRepo = $statementRepo;
        $this->registrationRepo = $registrationRepo;
    }

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

    public function getOpenBookings($executeQuery = true)
    {
        $currentDateTime = Carbon::now();

        $query = Booking::with('student', 'bedSpace')->whereHas('bedSpace', function ($query) use ($currentDateTime) {
            $query->where('is_available', '=', 'true');
        });

        return $executeQuery ? $query->get() : $query;
    }
    public function getClosedBookings($executeQuery = true)
    {
        $currentDateTime = Carbon::now();
        $query = Booking::with('student.user', 'bedSpace.room.hostel')
            ->whereHas('bedSpace', function ($query) use ($currentDateTime) {
                $query->where('is_available', '=', 'false')
                    ->whereDate('expiration_date', '>=', $currentDateTime);
            });

        $executeQuery ? $query->get() : $query;
    }
    public function getClosedBookingsOne($student_id, $executeQuery = false)
    {
        $currentDateTime = Carbon::now();

        $query = Booking::with('student.user', 'bedSpace.room.hostel')
            ->where('student_id', '=', $student_id)
            ->whereHas('bedSpace', function ($query) use ($currentDateTime) {
                $query->where('is_available', '=', 'false')
                    ->whereDate('expiration_date', '>=', $currentDateTime);
            });

        return $executeQuery ? $query->get() : $query;
    }

    public function invoiceStudent($student_id)
    {
        DB::beginTransaction();

        try {
            $student = Student::find($student_id);

            $studentIdsFromEnrollment = Enrollment::where('student_id', $student_id)
                ->first();
            $ac = AcademicPeriodClass::where('id', $studentIdsFromEnrollment->academic_period_class_id)->first();

            // Get next academic period
            $periodInfo = AcademicPeriod::find($ac->academic_period_id);

            // Process the student invoice using the helper function
            $this->processStudentInvoice($student, $periodInfo);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);  // Or handle the exception as needed
        }
    }
    private function processStudentInvoice($student, $periodInfo)
    {
        // Get academic fees associated with the student's program for the current academic period
        $academicFees = DB::table('academic_period_fees')
            ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
            ->where('academic_period_fees.academic_period_id', $periodInfo->id)
            ->select('academic_period_fees.*', 'programs.id as program_id')
            ->get();

        // Get universal fees (academic period fees with no associations)
        $universalFees = AcademicPeriodFee::whereHas('fee', function ($query) {
            $query->where('type', 'accommodation');
        })->where('academic_period_id', $periodInfo->id)
            ->get();

        // Create a new invoice
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_period_id' => $periodInfo->id,
            'raised_by' => Auth::user()->id
        ]);

        // Create invoice details for each academic fee
        foreach ($academicFees as $fee) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'fee_id' => $fee->fee_id,
                'amount' => $fee->amount
            ]);
        }

        // Create invoice details for each universal fee
        foreach ($universalFees as $ufee) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'fee_id' => $ufee->fee_id,
                'amount' => $ufee->amount
            ]);
        }

        // Finalize the invoice
        $this->finalizeInvoice($student, $invoice);
    }
    private function finalizeInvoice($student, $invoice)
    {
        // Commit the transaction
        DB::commit();

        // Apply any negative amounts to the invoice
        $this->statementRepo->applyNegativeAmountToInvoice($student, $invoice);

        // Remove any zero amount statements
        $this->statementRepo->removeZeroStatementAmounts();
    }
    public function UpdateBookingStatus($student_id, $ac, $amount)
    {
        //get amount for the accommodation
        $currentDateTime = Carbon::now();
        $booking = Booking::with('student.user', 'bedSpace.room.hostel')->where('student_id', '=', $student_id)->whereHas('bedSpace', function ($query) use ($currentDateTime) {
            $query->where('is_available', '=', 'false')->whereDate('expiration_date', '>=', $currentDateTime);
        })->first();
        if ($booking) {
            $feeName = Fee::where('name', 'accommodation')->first();
            $fee = Invoice::with('details')->where('student_id', $student_id)->whereHas('details', function ($query) use ($feeName) {
                $query->where('fee_id', $feeName->id);
            })->where('academic_period_id', $ac)
                ->first();
            foreach ($fee->details as $detail) {
                if ($detail->amount <= $amount && $feeName->id == $detail->fee_id) {
                    $aca = AcademicPeriod::find($ac);
                    $data['expiration_date'] = $aca->ac_end_date;
                    $update = $this->booking_repository->update($booking->id, $data);
                }
            }
        }
    }
}
