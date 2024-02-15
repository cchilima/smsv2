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


            // check if student have already been invoiced

            foreach ($students as $student) {

                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

                if (!$exists) {
                    array_push($students_filtered_ids, $student->id);
                }
            }

  

            // get academic period fees
            $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

            // invoice the students
            foreach ($students_filtered_ids as $student_id) {
                // create invoice
                $invoice = Invoice::create(['student_id' => $student_id, 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);

                foreach ($fees as $fee) {
                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->id, 'amount' => $fee->amount]);
                }

                // get student
                $student_obj = $this->getStudent($student_id);

                // check for any hanging money and push any money found towards invoice
                $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

                // Remove any zero amount statements
                $this->statementRepo->removeZeroStatementAmounts();
            }


            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollback();
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
                // get academic period fees
                $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

                // invoice the student

                // create invoice
                $invoice = Invoice::create(['student_id' => $student->id, 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);

                foreach ($fees as $fee) {
                    // create invoice details
                    $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id, 'fee_id' => $fee->id, 'amount' => $fee->amount]);
                }

                // check for any hanging money and push any money found towards invoice
                $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

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

