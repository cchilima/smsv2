<?php

namespace App\Repositories\Accounting;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriodFee, AcademicPeriod};
use App\Repositories\Academics\{StudentRegistrationRepository};
use App\Models\Accounting\{Invoice, InvoiceDetail};
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
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

    public function invoiceStudents($academic_period_id)
    {
        DB::beginTransaction();

        try {
            // filtered student list
            $students_filtered_ids = [];

            // get all students with academic period
            $academic_period = AcademicPeriod::find($academic_period_id);
            $full_academic_period_info = $academic_period->academic_period_information;

            $students = Student::/* where('academic_period_intake_id', $full_academic_period_info->academic_period_intake_id)-> */ where('period_type_id', $academic_period->period_type_id)->where('study_mode_id', $full_academic_period_info->study_mode_id)->get();

            // tracks all programs of students
            $programs_lists = [];
            $student_program_ids = [];

            // check if student have already been invoiced

            foreach ($students as $student) {
                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

                if (!$exists) {
                    // array_push($students_filtered_ids, $student->id);
                    // array_push($programs_lists, $student->program->id);

                    $students_filtered_ids[] = ['student_id' => $student->id, 'program_id' => $student->program->id];

                    $student_program_ids[$student->id] = $student->program->id;
                }
            }

            // Get academic period fees associated with the student's program for the specified academic period
            // $academicFees = $student->program->academicPeriodFees->where('academic_period_id', $academic_period_id)->with('programs');

            // Ensure there are students to invoice
            if (empty($student_program_ids)) {
                DB::commit();
                return false;  // No students to invoice
            }

            // Fetch all unique programs for filtered students
            $program_ids = array_values(array_unique(array_values($student_program_ids)));

            // Get academic period fees associated with the students' programs for the specified academic period
            /*$academicFees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)
            ->whereHas('programs', function ($query) use ($program_ids) {
                $query->whereIn('programs.id', $program_ids);
            })->with('programs')
            ->get();*/

            $academicFees = DB::table('academic_period_fees')
                ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
                ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
                ->where('academic_period_fees.academic_period_id', $academic_period_id)
                ->whereIn('programs.id', $program_ids)
                ->select('academic_period_fees.*', 'programs.id as program_id')
                ->get();

            // Get universal fees (academic period fees with no associations)
            $universalFees = AcademicPeriodFee::doesntHave('programs')->get();

            // Merge academic fees and universal fees
            // $fees = $academicFees->merge($universalFees);

            // $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

            // invoice the students
            foreach ($students_filtered_ids as $student) {
                // create invoice
                $invoice = Invoice::create(['student_id' => $student['student_id'], 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);

                foreach ($academicFees as $fee) {
                    // create invoice details
                    if ($student['program_id'] == $fee->program_id) {
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                    }
                }

                foreach ($universalFees as $ufee) {
                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $ufee->fee_id, 'amount' => $ufee->amount]);
                }

                // get student
                $student_obj = $this->getStudent($student['student_id']);

                // check for any hanging money and push any money found towards invoice
                $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

                // Remove any zero amount statements
                $this->statementRepo->removeZeroStatementAmounts();
            }

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
            // get student object
            $student_obj = $this->getStudent($student_id);

            // get next academic period
            $periodInfo = $this->openAcademicPeriod($student_obj);

            // get academic period
            $academic_period = AcademicPeriod::find($periodInfo->academic_period_id);
            $full_academic_period_info = $academic_period->academic_period_information;

            // check if student have already been invoiced
            $exists = Invoice::where('student_id', $student_obj->id)->where('academic_period_id', $periodInfo->academic_period_id)->exists();

            // get previous academic period
            $previousPeriod = $this->latestPreviousAcademicPeriod($student_obj);



            if( $previousPeriod){

            // review previous academic period results and decide on billing
            $resultsReview = $this->registrationRepo->results($student_obj->id, $previousPeriod->academic_period_id);




            if($resultsReview){

                if(count($resultsReview['coursesFailed']) >= 3){

                   $crf = AcademicPeriodFee::doesntHave('programs')->whereHas('fee', function($query) { $query->where('type', 'course repeat fee');})->first();

                   $bill = ($crf->amount * $resultsReview['coursesFailed']);

                    // create invoice
                    $invoice = Invoice::create(['student_id' => $student_obj->id, 'academic_period_id' => $periodInfo->academic_period_id, 'raised_by' => Auth::user()->id]);

                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $crf->fee_id, 'amount' => $crf->amount]);

                    DB::commit();

                    return true;

                }
            }

            }


            

            if($previousPeriod){

                // get previous academic period fees
                $previousFees = $this->getLastAcademicPeriodFees($student_obj, $previousPeriod->academic_period_id);

                if (!$exists) { 

                // create invoice
                $invoice = Invoice::create(['student_id' => $student_obj->id, 'academic_period_id' => $periodInfo->academic_period_id, 'raised_by' => Auth::user()->id]);

                foreach ($previousFees['fees'] as $fee) {
                    // create invoice details
                    if ($student_obj->program_id == $fee->program_id) {
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                    }
                }

                foreach ($previousFees['universal_fees'] as $ufee) {
                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $ufee->fee_id, 'amount' => $ufee->amount]);
                }

                /*foreach ($fees as $fee) {
                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                }*/

                // check for any hanging money and push any money found towards invoice
                $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

                // dd($x);

                // Remove any zero amount statements
                $this->statementRepo->removeZeroStatementAmounts();

                }

            } else {

                if (!$exists) {
                    // Get academic period fees associated with the student's program for the specified academic period
                    // $academicFees = $student->program->academicPeriodFees->where('academic_period_id', $academic_period_id);
    
                    $academicFees = DB::table('academic_period_fees')
                        ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
                        ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
                        ->where('academic_period_fees.academic_period_id', $periodInfo->academic_period_id)
                        ->where('programs.id', $student_obj->program_id)
                        ->select('academic_period_fees.*', 'programs.id as program_id')
                        ->get();
    
                    // Get universal fees (academic period fees with no associations)
                    $universalFees = AcademicPeriodFee::doesntHave('programs')->whereHas('fee', function($query) { $query->whereNot('type', 'course repeat fee');})->get();


                    // Merge academic fees and universal fees
                    // $fees = $academicFees->merge($universalFees);
    
                    // get academic period fees
                    // $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();
    
                    // invoice the student
    
                    // create invoice
                    $invoice = Invoice::create(['student_id' => $student_obj->id, 'academic_period_id' => $periodInfo->academic_period_id, 'raised_by' => Auth::user()->id]);
    
                    foreach ($academicFees as $fee) {
                        // create invoice details
                       $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                    }
    
                    foreach ($universalFees as $ufee) {
                        // create invoice details
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $ufee->fee_id, 'amount' => $ufee->amount]);
                    }
    
                    /*foreach ($fees as $fee) {
                        // create invoice details
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                    }*/
    
                    // check for any hanging money and push any money found towards invoice
                    $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);
    
                    // dd($x);
    
                    // Remove any zero amount statements
                    $this->statementRepo->removeZeroStatementAmounts();
                }
    

            }
            
            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollback();
        }
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

    private function getLastAcademicPeriodFees($student,$academic_period_id)
    {
       
         $fees = DB::table('academic_period_fees')
                    ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
                    ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
                    ->join('fees', 'fees.id' , '=', 'academic_period_fees.fee_id')
                    ->where('fees.type', 'recurring')
                    ->where('academic_period_fees.academic_period_id', $academic_period_id)
                    ->where('programs.id', $student->program_id)
                    ->select('academic_period_fees.*', 'programs.id as program_id')
                    ->get();

       // $universalFees = AcademicPeriodFee::doesntHave('programs')->with('fee')->where('type', 'recurring')->get();

        $universalFees = AcademicPeriodFee::doesntHave('programs')->whereHas('fee', function($query) { $query->where('type', 'recurring');})->get();

        return ['fees' => $fees, 'universal_fees' => $universalFees ];

    }
}
