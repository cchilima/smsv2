<?php

namespace App\Repositories\Accounting;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriodFee, AcademicPeriod};
use App\Models\Accounting\{Invoice, InvoiceDetail};
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use Auth;
use DB;
// use App\Repositories\Accounting\StatementRepository;

class InvoiceRepository
{
    protected $statementRepo;

    public function __construct(StatementRepository $statementRepo)
    {
        $this->statementRepo = $statementRepo;
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

            $students = Student::where('academic_period_intake_id', $full_academic_period_info->academic_period_intake_id)->where('period_type_id', $academic_period->period_type_id)->where('study_mode_id', $full_academic_period_info->study_mode_id)->get();

            // tracks all programs of students
            $programs_lists = [];
            $student_program_ids = [];
            

            // check if student have already been invoiced

            foreach ($students as $student) {

                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

                if (!$exists) {
                    //array_push($students_filtered_ids, $student->id);
                    //array_push($programs_lists, $student->program->id);

                    $students_filtered_ids[] = ['student_id' => $student->id, 'program_id' => $student->program->id];

                    $student_program_ids[$student->id] = $student->program->id;
                }
            }

  
            // Get academic period fees associated with the student's program for the specified academic period
            //$academicFees = $student->program->academicPeriodFees->where('academic_period_id', $academic_period_id)->with('programs');

            // Ensure there are students to invoice
        if (empty($student_program_ids)) {
            DB::commit();
            return false; // No students to invoice
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
            //$fees = $academicFees->merge($universalFees);

           // $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

            // invoice the students
            foreach ($students_filtered_ids as $student) {
                // create invoice
                $invoice = Invoice::create(['student_id' => $student['student_id'], 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);

                foreach ($academicFees as $fee) {
                    // create invoice details
                    if($student['program_id'] == $fee->program_id){
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

            // get academic period
            $academic_period = AcademicPeriod::find($academic_period_id);
            $full_academic_period_info = $academic_period->academic_period_information;

            $student = Student::where('academic_period_intake_id', $full_academic_period_info->academic_period_intake_id)->where('period_type_id', $academic_period->period_type_id)->where('study_mode_id', $full_academic_period_info->study_mode_id)->where('id', $student_id)->first();

            // check if student have already been invoiced

            $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

            if (!$exists) {

                // Get academic period fees associated with the student's program for the specified academic period
                //$academicFees = $student->program->academicPeriodFees->where('academic_period_id', $academic_period_id);

                $academicFees = DB::table('academic_period_fees')
                ->join('program_academic_period_fee', 'academic_period_fees.id', '=', 'program_academic_period_fee.academic_period_fee_id')
                ->join('programs', 'program_academic_period_fee.program_id', '=', 'programs.id')
                ->where('academic_period_fees.academic_period_id', $academic_period_id)
                ->whereIn('programs.id', $student->program_id)
                ->select('academic_period_fees.*', 'programs.id as program_id')
                ->get();

                // Get universal fees (academic period fees with no associations)
                $universalFees = AcademicPeriodFee::doesntHave('programs')->get();

                // Merge academic fees and universal fees
                //$fees = $academicFees->merge($universalFees);
                
                // get academic period fees
               // $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

                // invoice the student

                // create invoice
                $invoice = Invoice::create(['student_id' => $student->id, 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);



                foreach ($academicFees as $fee) {
                    // create invoice details
                    if($student->program_id == $fee->program_id){
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->fee_id, 'amount' => $fee->amount]);
                    }
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
}

