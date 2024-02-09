<?php

namespace App\Repositories\Accounting;

use DB;
use Auth;
use App\Model\Admission\Student;
use App\Models\Accounting\{Statement, Invoice, Receipt};

class StatementRepository
{

        public function collectPayment($amount, $academic_period_id, $student_id)
        {
            // original amount received
            $original_amount_collected = $amount ? $amount : 0;

            // amount received, this one is being altered in the code below
            $amount_collected = $amount ? $amount : 0;

            // invoice to attach amount to
            $invoice_id = null;

            DB::beginTransaction();
            
            try {
                
                // determine the total amount paid by student so far

                $invoices = Invoice::join('students', 'students.id', 'invoices.student_id')
                ->join('invoice_details', 'invoice_details.invoice_id', 'invoices.id')
                ->select('invoices.*', DB::raw('SUM(invoice_details.amount) as fee_total'))
                ->selectSub(function ($query) {
                    $query->select(DB::raw('SUM(amount)'))
                        ->from('statements')
                        ->whereColumn('invoices.id', 'statements.invoice_id')
                        ->groupBy('statements.invoice_id');
                }, 'statements_total')
                ->where('student_id', $student_id)
                ->groupBy('invoices.id')
                ->orderBy('invoices.created_at')
                ->get();

                // Determine if all invoices have been cleared 

                $nonInvoicePayment = null;

            foreach ($invoices as $invoice) { 

                    if(($invoice->statements_total ?? 0) == $invoice->fee_total){
                        $nonInvoicePayment = true;
                    } else {
                        $nonInvoicePayment = false;
                    }
                } 

                if($nonInvoicePayment){

                    // create statement
                    $this->createStatement(null, $amount_collected, $student_id);

                    // create receipt
                    $amount_collected > 0 ? $this->createReceipt(null, $original_amount_collected, $student_id) : '';

                } else {

                // loop through invoices and determine which invoice to attached received amount.
            
                foreach ($invoices as $invoice) {

                if($invoice->statements_total ?? 0 < $invoice->fee_total){

                    $invoice_balance = $invoice->fee_total - ($invoice->statements_total ?? 0);

                    if($amount_collected > $invoice_balance){
                        
                        $amount_collected = $amount_collected - $invoice_balance;

                        $this->createStatement($invoice->id, $invoice_balance, $student_id);
                    
                    } else {

                        if($amount_collected != 0){

                            $this->createStatement($invoice->id, $amount_collected, $student_id);

                            $amount_collected > 0 ? $this->createReceipt($invoice->id, $original_amount_collected, $student_id) : '';

                            $amount_collected = 0;

                            break;
                        }
                    
                    }

                } 
            }

            if($amount_collected > 0){

                // create statement
                $this->createStatement(null, $amount_collected, $student_id);

                // create receipt
                $amount_collected ? $this->createReceipt(null, $original_amount_collected, $student_id) : '';
            } 

        }

            DB::commit();

            return true;

            } catch (\Exception $e) {
                dd($e);
                DB::rollback();

        }
    }


    private function createStatement($invoice_id, $amount, $student_id)
    {
        $statement = Statement::create([
            'invoice_id' => $invoice_id,
            'amount' => $amount,
            'collected_by' => Auth::user()->id,
            'collected_from' => $student_id
        ]);

        return $statement;
    }


    private function createReceipt($invoice_id, $amount, $student_id)
    {
        $receipt = Receipt::create([
            'invoice_id' => $invoice_id,
            'amount' => $amount,
            'collected_by' => Auth::user()->id,
            'student_id' => $student_id
        ]);

        return $receipt;
    }


    public function applyNegativeAmountToInvoice($student, $invoice)
    {
                // lorem ipsum
                $new_statement_amount = 0;
       
                // get amount in negative
                $amount = $student->statementsWithoutInvoice->sum('amount');
                $invoice_total = $invoice->details->sum('amount');
        
                if($amount == $invoice_total){

                    $statements_to_zero = $student->statementsWithoutInvoice->pluck('id');

                    Statement::whereIn('id', $statements_to_zero)->update(['amount' => 0]);

                    // create statement
                    $this->createStatement($invoice->id, $amount, $student->id);

                    // create receipt
                    //$this->createReceipt($invoice->id, $amount_collected, $student_id);

                
                } else {

                    if($amount < $invoice_total){

                     //   dd("statement amount less than invoice total");

                        $invoice_deduction = $invoice_total - $amount;

                        // create statement
                        $this->createStatement($invoice->id, $amount, $student->id);

                        // create receipt
                        //$this->createReceipt($invoice->id, $amount_collected, $student_id);

                        foreach ($student->statementsWithoutInvoice as $statement) {

                            if($statement->amount == $amount){
                                $statement->update(['amount' => 0]);
                            } elseif ($statement->amount < $amount){
                               // $new_statement_amount += $amount - $statement->amount;
                                $statement->update(['amount' => 0]);
                            } elseif( $statement->amount > $amount ) {
                               // $new_statement_amount += $statement->amount - $amount;
                                $statement->update(['amount' => ($statement->amount - $amount)]);
                            }
                        }

                       // $this->createStatement(null, $new_statement_amount, $student->id);

                      //  $new_statement_amount = 0;

                    } elseif($amount > $invoice_total) {

                       // dd("statement amount greater than invoice total");

                        $statement_deduction = $amount - $invoice_total;

                        // create statement
                        $this->createStatement($invoice->id, $invoice_total, $student->id);

                        // create receipt
                        //$this->createReceipt($invoice->id, $amount_collected, $student_id);

                        foreach ($student->statementsWithoutInvoice as $statement) {

                            if($statement->amount == $amount){
                               // dd("is equal to");
                                $statement->update(['amount' => ($statement->amount - $invoice_total)]);
                            } elseif ($statement->amount < $amount){
                                //dd("statement amount is less than statements sum total");
                                // $new_statement_amount += $amount - $statement->amount;
                                $statement->update(['amount' => 0]);

                            } 
                        }

                       // $this->createStatement(null, $new_statement_amount, $student->id);

                      //  $new_statement_amount = 0;

                    }

                    
                }

    }


}
