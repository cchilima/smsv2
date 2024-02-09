<?php

namespace App\Repositories\Accounting;

use DB;
use Auth;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Models\Accounting\{Invoice, InvoiceDetail};
use App\Models\Academics\{AcademicPeriodClass, AcademicPeriodFee};
//use App\Repositories\Accounting\StatementRepository;

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

            // get all students enrolled in acacdemic period

            $students = AcademicPeriodClass::join('enrollments', 'enrollments.academic_period_class_id', 'academic_period_classes.id')
                ->join('students', 'enrollments.user_id', 'students.user_id')
                ->where('academic_period_id', $academic_period_id)
                ->select('students.id') 
                ->distinct()
                ->get(); 

            
            // check if student have already been invoiced

            foreach ($students as $student) {

                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();

                if(!$exists){
                    array_push($students_filtered_ids, $student->id);
                }
            }

            // get academic period fees
            $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

            // invoice the students
            foreach ($students_filtered_ids as $student_id) {

            // create invoice
            $invoice = Invoice::create(['student_id' => $student->id, 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);
            
            foreach ($fees as $fee) {
                // create invoice details
                $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id,'fee_id' => $fee->id, 'amount' => $fee->amount]);
            }


            // get student 
            $student_obj = $this->getStudent($student_id);

            // check for any hanging money and push any money found towards invoice
            $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

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
        
            // get all students enrolled in acacdemic period

            $student = AcademicPeriodClass::join('enrollments', 'enrollments.academic_period_class_id', 'academic_period_classes.id')
                ->join('students', 'enrollments.user_id', 'students.user_id')
                ->where('academic_period_id', $academic_period_id)
                ->where('students.id', $student_id)
                ->select('students.id') 
                ->distinct()
                ->first(); 

            
            // check if student have already been invoiced

                $exists = Invoice::where('student_id', $student->id)->where('academic_period_id', $academic_period_id)->exists();


                if(!$exists){

                    // get academic period fees
                    $fees = AcademicPeriodFee::where('academic_period_id', $academic_period_id)->get();

                    // invoice the student

                    // create invoice
                    $invoice = Invoice::create(['student_id' => $student->id, 'academic_period_id' => $academic_period_id, 'raised_by' => Auth::user()->id]);
                    
                    foreach ($fees as $fee) {
                        // create invoice details
                        $invoiceDetails = InvoiceDetail::create(['invoice_id' => $invoice->id,'fee_id' => $fee->id, 'amount' => $fee->amount]);
                    }

                    // check for any hanging money and push any money found towards invoice
                     $this->statementRepo->applyNegativeAmountToInvoice($student_obj, $invoice);

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



















    /*$students = AcademicPeriodClass::with(['enrollments.student'])
    ->where('academic_period_id', $academic_period_id)
    ->get();*/