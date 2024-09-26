<?php

namespace App\Repositories\Accounting;

use App\Model\Admission\Student;
use App\Models\Accounting\{Statement, Invoice, Receipt, PaymentMethod};
use Auth;
use DB;

class StatementRepository
{
    public function collectPayment($amount, $academic_period_id, $student_id, $payment_method_id)
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
                    $query
                        ->select(DB::raw('SUM(amount)'))
                        ->from('statements')
                        ->whereColumn('invoices.id', 'statements.invoice_id')
                        ->groupBy('statements.invoice_id');
                }, 'statements_total')
                ->where('student_id', $student_id)
                ->groupBy('invoices.id','invoices.student_id', 'invoices.academic_period_id', 'invoices.raised_by', 'invoices.cancelled', 'invoices.created_at', 'invoices.updated_at' )
                ->orderBy('invoices.created_at')
                ->get();

            // Determine if all invoices have been cleared

            $nonInvoicePayment = null;

            foreach ($invoices as $invoice) {
                if (($invoice->statements_total ?? 0) == $invoice->fee_total) {
                    $nonInvoicePayment = true;
                } else {
                    $nonInvoicePayment = false;
                }
            }

            if ($nonInvoicePayment) {
                // create statement
                $this->createStatement(null, $amount_collected, $student_id, $payment_method_id);

                // create receipt
                $amount_collected > 0 ? $this->createReceipt(null, $original_amount_collected, $student_id, $payment_method_id) : '';

            } else {
                // loop through invoices and determine which invoice to attached received amount.

                foreach ($invoices as $invoice) {
                    if ($invoice->statements_total ?? 0 < $invoice->fee_total) {
                        $invoice_balance = $invoice->fee_total - ($invoice->statements_total ?? 0);

                        if ($amount_collected > $invoice_balance) {
                            $amount_collected = $amount_collected - $invoice_balance;

                            $this->createStatement($invoice->id, $invoice_balance, $student_id, $payment_method_id);

                        } else {

                            if ($amount_collected != 0) {

                                $this->createStatement($invoice->id, $amount_collected, $student_id, $payment_method_id);

                                $amount_collected > 0 ? $this->createReceipt($invoice->id, $original_amount_collected, $student_id, $payment_method_id) : '';

                                $amount_collected = 0;

                                break;
                            }
                        }
                    }
                }

                if ($amount_collected > 0) {
                    // create statement
                    $this->createStatement(null, $amount_collected, $student_id, $payment_method_id);

                    // create receipt
                    $amount_collected ? $this->createReceipt(null, $original_amount_collected, $student_id, $payment_method_id) : '';
                }
            }

            DB::commit();

            return true;

        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }

    private function createStatement($invoice_id, $amount, $student_id, $payment_method_id = null)
    {
        $statement = Statement::create([
            'invoice_id' => $invoice_id,
            'amount' => $amount,
            'collected_by' => Auth::user()->id,
            'collected_from' => $student_id,
            'payment_method_id' => $payment_method_id,
        ]);

        return $statement;
    }

    public function createReceipt($invoice_id, $amount, $student_id, $payment_method_id = null)
    {

        $receipt = Receipt::create([
            'invoice_id' => $invoice_id,
            'amount' => $amount,
            'collected_by' => Auth::user()->id,
            'student_id' => $student_id,
            'payment_method_id' => $payment_method_id,
        ]);

        return $receipt;
    }

    public function applyNegativeAmountToInvoice($student, $invoice) {

        // Get amount in negative
        $negative_total = $student->statementsWithoutInvoice->sum('amount');

        // Get invoiced total
        $invoice_total = $invoice->details->sum('amount');

        // If amount greater than 0 continue computation
        if($negative_total > 0 ){

            // Determine payment method
            $pay_method_ids = $student->statementsWithoutInvoice->pluck('payment_method_id');

            // Get id with the most occurances
            $payment_method_id = array_keys(array_count_values($pay_method_ids->toArray()), max(array_count_values($pay_method_ids->toArray())))[0];
            $payment_method_id = (int)$payment_method_id;

            if($negative_total == $invoice_total){

                // Get all statements to zero
                $statements_to_zero = $student->statementsWithoutInvoice->pluck('id');

                // Proceed to zero statements
                $statement = Statement::whereIn('id', $statements_to_zero)->update(['amount' => 0]);

                // Create new statement , to show that collective statement were applied to invoice
                $this->createStatement($invoice->id, $negative_total, $student->id, $payment_method_id);

                // Create a receipt
                $this->createReceipt($invoice->id, $negative_total, $student->id, $payment_method_id);

            } elseif ($negative_total < $invoice_total) {

                // Create a receipt
                //$this->createReceipt($invoice->id, $negative_total, $student->id, $payment_method_id);

                // Create new statement, to show that collective statement were applied to invoice
                $this->createStatement($invoice->id, $negative_total, $student->id, $payment_method_id);

                $currentAmountRemaining = $negative_total;

                // Determine which statement to deduct amount from
                foreach ($student->statementsWithoutInvoice as $statement) {

                    if ($statement->amount == $currentAmountRemaining) {
                        $statement->update(['amount' => 0]);
                        break;

                    } elseif ($statement->amount < $currentAmountRemaining) {
                        $currentAmountRemaining -= $statement->amount;
                        $statement->update(['amount' => 0]);

                    } elseif ($statement->amount > $currentAmountRemaining) {
                        $statement->update(['amount' => ($statement->amount - $currentAmountRemaining)]);
                        break;
                    }
                }

            } elseif ($negative_total > $invoice_total) {

                // Calculate remaining negative total after applying invoice total
                $remainingNegativeTotal = $negative_total - $invoice_total;

                // Create new statement, to show that collective statement were applied to invoice
                $this->createStatement($invoice->id, $invoice_total, $student->id, $payment_method_id);

                // Create a receipt
                $this->createReceipt($invoice->id, $invoice_total, $student->id, $payment_method_id);

                // Iterate over statements to distribute remaining negative total
                foreach ($student->statementsWithoutInvoice as $statement) {

                    if ($remainingNegativeTotal <= 0) {
                        break; // No more amount remaining to distribute
                    }

                    if($statement->amount > $invoice_total ){
                        $statement->update(['amount' => $remainingNegativeTotal]);
                        break;
                    }

                    /*

                    if ($statement->amount <= $negative_total) {

                        // Update statement amount to zero and reduce remaining negative total
                        $remainingNegativeTotal -= $statement->amount;
                        $statement->update(['amount' => 0]);

                    } else {
                        // Reduce statement amount by remaining negative total
                        $statement->update(['amount' => ($statement->amount - $remainingNegativeTotal)]);
                        $remainingNegativeTotal = 0;
                    }

                    */

                }
            }

        }
   }

    public function removeZeroStatementAmounts()
    {
        Statement::where('amount', 0.0)->delete();
    }
}
