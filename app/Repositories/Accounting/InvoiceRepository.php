<?php

namespace App\Repositories\Accounting;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriodFee, AcademicPeriod};
use App\Repositories\Academics\{StudentRegistrationRepository};
use App\Models\Accounting\{Invoice, InvoiceDetail};
use App\Models\Enrollments\Enrollment;
use App\Models\Admissions\Student;
use Auth;
use DB;

class InvoiceRepository
{
    protected $statementRepo;
    protected $registrationRepo;

    public function __construct(StatementRepository $statementRepo, StudentRegistrationRepository $registrationRepo)
    {
        $this->statementRepo = $statementRepo;
        $this->registrationRepo = $registrationRepo;
    }

    private function getStudent($student_id)
    {
        return Student::find($student_id);
    }

    public function customInvoiceStudent($amount, $fee_id, $student_id)
    {
        DB::beginTransaction();

        try {
            // get student
            $student = $this->getStudent($student_id);

            // create invoice
            $invoice = Invoice::create(['student_id' => $student->id, 'academic_period_id' => $student->academic_info->academic_period->id, 'raised_by' => Auth::user()->id]);

            // create invoice details
            $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee_id, 'amount' => $amount]);

            // check for any hanging money and push any money found towards invoice
            $this->statementRepo->applyNegativeAmountToInvoice($student, $invoice);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function invoiceStudent($academic_period_id, $student_id)
    {
        DB::beginTransaction();

        try {
            // Get student object
            $student = $this->getStudent($student_id);

            // Get next academic period
            $periodInfo = $this->openAcademicPeriod($student);

            // Process the student invoice using the helper function
            $this->processStudentInvoice($student, $periodInfo);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);  // Or handle the exception as needed
        }
    }

    public function invoiceStudents($academic_period_id)
    {
        DB::beginTransaction();

        try {
            // non-invoiced student list
            $student_id_list = [];

            // Get the academic period and related information
            $academic_period = AcademicPeriod::find($academic_period_id);
            $full_academic_period_info = $academic_period->academic_period_information;

            // Fetch students matching the academic period and study mode
            $students = Student::where('period_type_id', $academic_period->period_type_id)
                ->where('study_mode_id', $full_academic_period_info->study_mode_id)
                ->get();

            // check if student have already been invoiced
            foreach ($students as $student) {
                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

                if (!$exists) {
                    array_push($student_id_list, $student->id);
                }
            }

            // Ensure there are students to invoice
            if (empty($student_id_list)) {
                DB::commit();
                return false;  // No students to invoice
            }

            // Process each student for invoicing
            foreach ($student_id_list as $student_id) {
                $student = $this->getStudent($student_id);
                $periodInfo = $this->openAcademicPeriod($student);
                $this->processStudentInvoice($student, $periodInfo);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    private function processStudentInvoice($student, $periodInfo)
    {
        // Check if the student has already been invoiced for the new period
        $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $periodInfo->academic_period_id)->exists();

        // Get the latest previous academic period
        $previousPeriod = $this->latestPreviousAcademicPeriod($student);

        if ($previousPeriod) {
            // Review previous academic period results
            $resultsReview = $this->registrationRepo->results($student->id, $previousPeriod->academic_period_id);
            if ($resultsReview && count($resultsReview['coursesFailed']) >= 3) {
                // Create an invoice for course repeats if applicable
                $this->createCourseRepeatInvoice($student, $periodInfo, $resultsReview);
                return;
            }
        }

        if ($previousPeriod) {
            // Get fees from the previous academic period
            $previousFees = $this->getLastAcademicPeriodFees($student, $previousPeriod->academic_period_id);
            if (!$exists) {
                // Create an invoice based on previous fees
                $this->createInvoiceFromPreviousFees($student, $periodInfo, $previousFees);
            }
        } else {
            if (!$exists) {
                // Create an invoice based on current academic period fees
                $this->createInvoiceFromCurrentPeriodFees($student, $periodInfo);
            }
        }
    }

    private function createCourseRepeatInvoice($student, $periodInfo, $resultsReview)
    {
        // Get the course repeat fee
        $crf = AcademicPeriodFee::doesntHave('programs')
            ->whereHas('fee', function ($query) {
                $query->where('type', 'course repeat fee');
            })
            ->first();

        // Calculate the total amount for course repeats
        $bill = $crf->amount * count($resultsReview['coursesFailed']);

        // Create a new invoice
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_period_id' => $periodInfo->academic_period_id,
            'raised_by' => Auth::user()->id
        ]);

        // Create invoice details for each failed course
        foreach ($resultsReview['coursesFailed'] as $courseRepeat) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'fee_id' => $crf->fee_id,
                'amount' => $crf->amount
            ]);
        }

        // Finalize the invoice
        $this->finalizeInvoice($student, $invoice);
    }

    private function createInvoiceFromPreviousFees($student, $periodInfo, $previousFees)
    {
        // Create a new invoice
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_period_id' => $periodInfo->academic_period_id,
            'raised_by' => Auth::user()->id
        ]);

        // Create invoice details for each program-specific fee
        foreach ($previousFees['fees'] as $fee) {
            if ($student->program_id == $fee->program_id) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'fee_id' => $fee->fee_id,
                    'amount' => $fee->amount
                ]);
            }
        }

        // Create invoice details for each universal fee
        foreach ($previousFees['universal_fees'] as $ufee) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'fee_id' => $ufee->fee_id,
                'amount' => $ufee->amount
            ]);
        }

        // Finalize the invoice
        $this->finalizeInvoice($student, $invoice);
    }

    private function createInvoiceFromCurrentPeriodFees($student, $periodInfo)
    {
        // Get academic fees associated with the student's program for the current academic period
        $academicFees = DB::table('academic_period_fees')
            ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
            ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
            ->where('academic_period_fees.academic_period_id', $periodInfo->academic_period_id)
            ->where('programs.id', $student->program_id)
            ->select('academic_period_fees.*', 'programs.id as program_id')
            ->get();

            // Get universal fees (academic period fees with no associations)
            $universalFees = AcademicPeriodFee::doesntHave('programs')
            ->whereHas('fee', function ($query) {
                $query->whereNotIn('type', ['course repeat fee', 'accommodation fee']);
            })
            ->get();


        // Create a new invoice
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_period_id' => $periodInfo->academic_period_id,
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

    public function openAcademicPeriod($student)
    {
        $currentDate = date('Y-m-d');

        // Get the next available academic period by joining with the academic_periods table
        $nextAcademicPeriod = DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $student->study_mode_id)
            ->where('academic_periods.ac_start_date', '<=', $currentDate)
            ->where('academic_periods.ac_end_date', '>=', $currentDate)
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->first();

        return $nextAcademicPeriod;
    }

    private function latestPreviousAcademicPeriod($student)
    {
        // Get the current date in 'YYYY-MM-DD' format
        $currentDate = date('Y-m-d');

        // Get the all closed available
        $latestClosedAcademicPeriod = DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $student->study_mode_id)
            ->where(function ($query) use ($currentDate) {
                $query->where('academic_periods.ac_end_date', '<', $currentDate)->orWhere('academic_periods.ac_start_date', '>', $currentDate);
            })
            ->orderBy('academic_periods.created_at', 'desc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->first();

        return $latestClosedAcademicPeriod;
    }

    private function getLastAcademicPeriodFees($student, $academic_period_id)
    {
        $fees = DB::table('academic_period_fees')
            ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
            ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
            ->join('fees', 'fees.id', '=', 'academic_period_fees.fee_id')
            ->where('fees.type', 'recurring')
            ->where('academic_period_fees.academic_period_id', $academic_period_id)
            ->where('programs.id', $student->program_id)
            ->select('academic_period_fees.*', 'programs.id as program_id')
            ->get();

        $universalFees = AcademicPeriodFee::doesntHave('programs')->whereHas('fee', function ($query) {
            $query->where('type', 'recurring');
        })->get();

        return ['fees' => $fees, 'universal_fees' => $universalFees];
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

    public function paymentPercentage($student_id)
    {
        $accumulative_total = 0;
        $accumulative_payments = 0;
    
        $student = $this->getStudent($student_id);
    
        foreach ($student->invoices as $key => $invoice) {
            $accumulative_total += $invoice->details->sum('amount');
            $accumulative_payments += $invoice->statements->sum('amount');
        }
    
        // Safeguard against division by zero
        if ($accumulative_total == 0) {
            return 0; // or you can choose another appropriate value or action
        }
    
        return (($accumulative_payments / $accumulative_total) * 100);
    }
    

}
