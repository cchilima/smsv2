<?php

namespace App\Repositories\Accounting;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriodFee, AcademicPeriod};
use App\Repositories\Academics\{StudentRegistrationRepository};
use App\Repositories\Admissions\{StudentRepository};
use App\Models\Accounting\{Invoice, InvoiceDetail, Receipt, Fee};
use App\Models\Enrollments\Enrollment;
use App\Models\Admissions\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuotationRepository
{

    protected StatementRepository $statementRepo;
    protected $registrationRepo;
    protected $studentRepo;

    public function __construct(StatementRepository $statementRepo, StudentRegistrationRepository $registrationRepo, StudentRepository $studentRepo)
    {
        $this->statementRepo = $statementRepo;
        $this->registrationRepo = $registrationRepo;
        $this->studentRepo = $studentRepo;
    }

    public function getInvoice($invoice_id)
    {
        return Invoice::find($invoice_id);
    }

    private function getStudent($student_id)
    {
        return Student::find($student_id);
    }



    public function getStudentQuotation($academic_period_id, $student_id)
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
        $invoiceExists = Invoice::where('student_id', $student->id)->where('academic_period_id', $periodInfo->academic_period_id)->exists();

        // Get the latest previous academic period
        $previousPeriod = $this->latestPreviousAcademicPeriod($student);

        if ($previousPeriod) {
            // Review previous academic period results
            $resultsReview = $this->registrationRepo->results($student->id, $previousPeriod->academic_period_id);

            dd($resultsReview);

            if ($resultsReview && count($resultsReview['coursesFailed']) >= 3) {
                // Create an invoice for course repeats if applicable
                $this->createCourseRepeatInvoice($student, $periodInfo, $resultsReview);
                return;
            }

            // Get fees from the previous academic period
            //$previousFees = $this->getLastAcademicPeriodFees($student, $previousPeriod->academic_period_id);

            // filter out once off fees

            // Get current academic period fees and filter one-time fees if needed
            $previousFees = $this->getFilteredStudentAcademicPeriodFees(
                $student,
                $previousPeriod->academic_period_id,
                true
            );

            if (!$invoiceExists) {
                // Get current academic period fees (once-off)
                $acFees = $this->getAcademicPeriodFees($student, $periodInfo->academic_period_id);

                $filteredFees = $acFees['fees']->filter(function ($fee) {
                    return $fee->type == 'once off';
                });

                $filteredUniversalFees = $acFees['universal_fees']->filter(function ($fee) {
                    return $fee->fee->type == 'once off';
                });

                // Merge the collections for both 'fees' and 'universal_fees'
                $mergedFees = $previousFees['fees']->merge($filteredFees);
                $mergedUniversalFees = $previousFees['universal_fees']->merge($filteredUniversalFees);

                // Update the $previousFees structure
                $previousFees['fees'] = $mergedFees;
                $previousFees['universal_fees'] = $mergedUniversalFees;

                // Create an invoice based on previous fees
                $this->createInvoiceFromPreviousFees($student, $periodInfo, $previousFees);
            }
        } else {
            if (!$invoiceExists) {
                // Create an invoice based on current academic period fees
                $this->createInvoiceFromCurrentPeriodFees($student, $periodInfo);
            }
        }
    }

    private function createCourseRepeatInvoice($student, $periodInfo, $resultsReview)
    {
        // dd($periodInfo);

        // Get the course repeat fee
        $crf = AcademicPeriodFee::doesntHave('programs')
            ->where('academic_period_id', $periodInfo['academic_period_id'])
            ->whereHas('fee', function ($query) {
                $query->where('type', 'course repeat fee');
            })
            ->first();

            if (!$crf) {

                $crf = AcademicPeriodFee::where('academic_period_id', $periodInfo['academic_period_id'])
                    ->whereHas('fee', function ($query) {
                        $query->where('type', 'course repeat fee');
                    })
                    ->whereHas('programs', function ($query) use ($student) {
                        $query->where('program_id', $student->program_id);
                    })
                    ->first();
            }


            // cant bill student if theres no course repeat fee
            if (!$crf) {
                return false;
            }
            

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
            ->join('fees', 'academic_period_fees.fee_id', '=', 'fees.id') // Join the fees table to access the fee types
            ->where('academic_period_fees.academic_period_id', $periodInfo->academic_period_id)
            ->where('programs.id', $student->program_id)
            ->whereNotIn('fees.type', ['course repeat fee', 'accommodation fee']) // Exclude the undesired fee types
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

    public function latestPreviousAcademicPeriod($student)
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
            ->skip(1)
            ->take(1)
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
            ->whereNotIn('fees.type', ['course repeat fee', 'accommodation fee'])
            ->select('academic_period_fees.*', 'programs.id as program_id')
            ->get();

        $universalFees = AcademicPeriodFee::doesntHave('programs')->whereHas('fee', function ($query) {
            $query->whereIn('type', ['recurring', 'once off']);
        })->with('fee:type,id')->get();

        return ['fees' => $fees, 'universal_fees' => $universalFees];
    }


    private function getAcademicPeriodFees($student, $academic_period_id)
    {
        $fees = DB::table('academic_period_fees')
            ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
            ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
            ->join('fees', 'fees.id', '=', 'academic_period_fees.fee_id')
            ->whereIn('fees.type', ['recurring', 'once off'])
            ->where('academic_period_fees.academic_period_id', $academic_period_id)
            ->where('programs.id', $student->program_id)
            ->whereNotIn('fees.type', ['course repeat fee', 'accommodation fee'])
            ->select('academic_period_fees.*', 'programs.id as program_id', 'fees.type')
            ->get();

        $universalFees = AcademicPeriodFee::doesntHave('programs')
            ->where('academic_period_id', $academic_period_id) // Added condition for academic_period_id
            ->whereHas('fee', function ($query) {
                $query->whereIn('type', ['recurring', 'once off']);
            })
            ->with('fee:type,id')
            ->get();

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

    public function invoiceTotal($invoice_id)
    {
        $invoice = $this->getInvoice($invoice_id);
        $accumulative_total = $invoice->details->sum('amount');

        return $accumulative_total;
    }










    private function getFilteredStudentAcademicPeriodFees($student, $academicPeriodId, $hasPastFees)
    {
        $acFees = $this->getAcademicPeriodFees($student, $academicPeriodId);

        if ($hasPastFees) {
            // Filter out one-time fees
            $acFees['fees'] = collect($acFees['fees'])->filter(function ($fee) {
                return $fee->type != 'once off';
            });

            $acFees['universal_fees'] = collect($acFees['universal_fees'])->filter(function ($ufee) {
                return $ufee->fee->type != 'once off';
            });
        }

        return $acFees;
    }

    private function calculatePercentage($cumulativeAmount, $total)
    {
        return $total == 0 ? 0 : (($cumulativeAmount / $total) * 100);
    }

    public function paymentPercentage($student_id, $getPrevious = null)
    {
        // Get the student
        $student = $this->getStudent($student_id);

        if ($getPrevious) {

            // Get the student's current academic period
            $academicPeriod = $this->latestPreviousAcademicPeriod($student);
        } else {
            // Get the student's current academic period
            $academicPeriod = $this->registrationRepo->getNextAcademicPeriod($student, now());
        }

        if (!$academicPeriod) {
            return 0;
        }

        // Get the student's cumulative academic period fees
        $acPastFeesTotal = $this->getAllPastFees($student, $academicPeriod->academic_period_id);

        // Get current academic period fees and filter one-time fees if needed
        $acFees = $this->getFilteredStudentAcademicPeriodFees(
            $student,
            $academicPeriod->academic_period_id,
            $acPastFeesTotal > 0
        );

        // Get custom invoiced fee that arent attached to academic period fees
        $customFeeTotal = $this->customInvoicedFeeTotal($student, $academicPeriod->academic_period_id);

        // Calculate total fees for the current academic period
        $acCurrentFeesTotal = ($acFees['fees']->sum('amount')) + ($acFees['universal_fees']->sum('amount') + $customFeeTotal);

        // Calculate total payments made by the student
        $totalPayments = $student->receipts->sum('amount') - $acPastFeesTotal;

        // Calculate and return the payment percentage
        return $this->calculatePercentage($totalPayments, $acCurrentFeesTotal);
    }




    public function getFees($student)
    {

        // get current academic period fees
        $academic_period_fees = $student->academic_info ? $student->academic_info->academic_period->academic_period_fees : [];

        // extract only the fee ids
        $academic_period_fee_ids = $academic_period_fees ? $academic_period_fees->pluck('fee_id') : [];

        // get all fees minus the fees in current academic period
        $fees = Fee::whereNotIn('id', $academic_period_fee_ids)->get();

        return $fees;
    }



    public function getStudentPaymentBalance($student_id, $getPrevious = null)
    {
        // Get the student
        $student = $this->getStudent($student_id);

        if ($getPrevious) {
            // Get the student's current academic period
            $academicPeriod = $this->latestPreviousAcademicPeriod($student);
        } else {
            // Get the student's current academic period
            $academicPeriod = $this->registrationRepo->getNextAcademicPeriod($student, now());
        }

        if ($academicPeriod == null) return 0;

        // Get the student's cumulative academic period fees
        $acPastFeesTotal = $this->getAllPastFees($student, $academicPeriod->academic_period_id);

        // Get current academic period fees and filter one-time fees if needed
        $acFees = $this->getFilteredStudentAcademicPeriodFees(
            $student,
            $academicPeriod->academic_period_id,
            $acPastFeesTotal > 0
        );

        // Get custom invoiced fee that arent attached to academic period fees
        $customFeeTotal = $this->customInvoicedFeeTotal($student, $academicPeriod->academic_period_id);

        // Calculate total fees for the current academic period
        $acCurrentFeesTotal = ($acFees['fees']->sum('amount')) + ($acFees['universal_fees']->sum('amount')) + $customFeeTotal;

        // Calculate total payments made by the student
        $totalPayments = $student->receipts->sum('amount') - $acPastFeesTotal;

        // Calculate and return the payment balance
        return $acCurrentFeesTotal - $totalPayments;
    }

    public function getStudentAcademicPeriodFeesTotal($student_id, $getPrevious = null)
    {
        // Get the student
        $student = $this->getStudent($student_id);

        if ($getPrevious) {

            // Get the student's current academic period
            $academicPeriod = $this->latestPreviousAcademicPeriod($student);
        } else {
            // Get the student's current academic period
            $academicPeriod = $this->registrationRepo->getNextAcademicPeriod($student, now());
        }

        if ($academicPeriod == null) return 0;

        // Get the student's cumulative academic period fees
        $acPastFeesTotal = $this->getAllPastFees($student, $academicPeriod->academic_period_id);

        // Get current academic period fees and filter one-time fees if needed
        $acFees = $this->getFilteredStudentAcademicPeriodFees(
            $student,
            $academicPeriod->academic_period_id,
            $acPastFeesTotal > 0
        );

        // Get custom invoiced fee that arent attached to academic period fees
        $customFeeTotal = $this->customInvoicedFeeTotal($student, $academicPeriod->academic_period_id);

        // Calculate total fees for the current academic period
        $acCurrentFeesTotal = ($acFees['fees']->sum('amount')) + ($acFees['universal_fees']->sum('amount')) + $customFeeTotal;

        // Calculate and return the fees total
        return $acCurrentFeesTotal;
    }

    public function getStudentAcademicPeriodPaymentsTotal($student_id, $getPrevious = null)
    {
        // Get the student
        $student = $this->getStudent($student_id);

        if ($getPrevious) {

            // Get the student's current academic period
            $academicPeriod = $this->latestPreviousAcademicPeriod($student);
        } else {
            // Get the student's current academic period
            $academicPeriod = $this->registrationRepo->getNextAcademicPeriod($student, now());
        }

        if ($academicPeriod == null) return 0;

        // Get the student's cumulative academic period fees
        $acPastFeesTotal = $this->getAllPastFees($student, $academicPeriod->academic_period_id);

        // Calculate total payments made by the student
        $totalPayments = $student->receipts->sum('amount') - $acPastFeesTotal;

        // Calculate and return the payments total
        return $totalPayments;
    }

    private function getAllPastFees($student, $ac_period_id)
    {
        $total = 0;

        $academicPeriodIds = Invoice::where('student_id', $student->id)
            ->where('academic_period_id', '!=', $ac_period_id)
            ->pluck('academic_period_id')
            ->unique();

        foreach ($academicPeriodIds as $period_id) {
            $fees = $this->getAcademicPeriodFees($student, $period_id);
            $total += ($fees['fees']->sum('amount')) + ($fees['universal_fees']->sum('amount'));
        }

        return $total;
    }


    /**
     * Get all invoices for a given student.
     *
     * @param  int  $studentId The ID of the student.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInvoicesByStudent($studentId)
    {
        return Invoice::with(['receipts', 'creditNotes'])
            ->where('student_id', $studentId)
            ->get();
    }


}