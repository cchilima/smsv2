<?php


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

            if($negative_total == $invoice_total){

                // Get all statements to zero 
                $statements_to_zero = $student->statementsWithoutInvoice->pluck('id');

                // Proceed to zero statements
                $statement = Statement::whereIn('id', $statements_to_zero)->update(['amount' => 0]);

                // Create new statement , to show that collective statement were applied to invoice
                $this->createStatement($invoice->id, $negative_total, $student->id, $payment_method_id);

                // Create a receipt 
                $this->createReceipt($invoice->id, $negative_total, $student_id, $payment_method_id);

            } elseif ($negative_total < $invoice_total) {

                // Create new statement, to show that collective statement were applied to invoice
                $this->createStatement($invoice->id, $negative_total, $student->id, $payment_method_id);
            
                // Create a receipt 
                $this->createReceipt($invoice->id, $negative_total, $student_id, $payment_method_id);
            
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
                $this->createStatement($invoice->id, $remainingNegativeTotal, $student->id, $payment_method_id);
                
                // Create a receipt 
                $this->createReceipt($invoice->id, $remainingNegativeTotal, $student_id, $payment_method_id);
            
                // Iterate over statements to distribute remaining negative total
                foreach ($student->statementsWithoutInvoice as $statement) {

                    if ($remainingNegativeTotal <= 0) {
                        break; // No more amount remaining to distribute
                    }

                    if ($statement->amount <= $remainingNegativeTotal) {
                        // Update statement amount to zero and reduce remaining negative total
                        $remainingNegativeTotal -= $statement->amount;
                        $statement->update(['amount' => 0]);
                    } else {
                        // Reduce statement amount by remaining negative total
                        $statement->update(['amount' => ($statement->amount - $remainingNegativeTotal)]);
                        $remainingNegativeTotal = 0;
                    }
                    
                }
            }

        }
   }







        //






        // Old code

        $new_statement_amount = 0;

        // get amount in negative
        $amount = $student->statementsWithoutInvoice->sum('amount');
        $invoice_total = $invoice->details->sum('amount');

        // determine payment method id
        if ($amount > 0) {
            $pay_method_ids = $student->statementsWithoutInvoice->pluck('payment_method_id');
            $payment_method_id = array_keys(array_count_values($pay_method_ids->toArray()), max(array_count_values($pay_method_ids->toArray())))[0];
        }

        // determine payment method id
        if ($amount > 0) {
            if ($amount == $invoice_total) {
                // determine statements to zero
                $statements_to_zero = $student->statementsWithoutInvoice->pluck('id');

                // zero statements
                $statement = Statement::whereIn('id', $statements_to_zero)->update(['amount' => 0]);

                // create statement
                $this->createStatement($invoice->id, $amount, $student->id, $payment_method_id);

                // create receipt
                $this->createReceipt($invoice->id, $amount_collected, $student_id, $payment_method_id);

            } else {
                if ($amount < $invoice_total) {

                   // dd($payment_method_id);
                    //dd('statement amount less than invoice total');

                    $invoice_deduction = $invoice_total - $amount;

                    dd($invoice_deduction);

                    // create statement
                   // $this->createStatement($invoice->id, $amount, $student->id, $payment_method_id);

                    // create receipt
                    $this->createReceipt($invoice->id, $amount, $student_id, $payment_method_id);


                    foreach ($student->statementsWithoutInvoice as $statement) {
                        if ($statement->amount == $amount) {
                            $statement->update(['amount' => 0]);
                        } elseif ($statement->amount < $amount) {
                            // $new_statement_amount += $amount - $statement->amount;
                            $statement->update(['amount' => 0]);
                        } elseif ($statement->amount > $amount) {
                            // $new_statement_amount += $statement->amount - $amount;
                            $statement->update(['amount' => ($statement->amount - $amount)]);
                        }
                    }

                    // $this->createStatement(null, $new_statement_amount, $student->id);

                    //  $new_statement_amount = 0;
                } elseif ($amount > $invoice_total) {
                    // dd("statement amount greater than invoice total");

                    $statement_deduction = $amount - $invoice_total;

                    // create statement
                    $this->createStatement($invoice->id, $invoice_total, $student->id, $payment_method_id);

                    // create receipt
                    $this->createReceipt($invoice->id, $amount_collected, $student_id, $payment_method_id);

                    foreach ($student->statementsWithoutInvoice as $statement) {
                        if ($statement->amount == $amount) {
                            // dd("is equal to");
                            $statement->update(['amount' => ($statement->amount - $invoice_total)]);
                        } elseif ($statement->amount < $amount) {
                            // dd("statement amount is less than statements sum total");
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