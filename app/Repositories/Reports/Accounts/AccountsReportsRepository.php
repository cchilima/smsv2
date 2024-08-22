<?php

namespace App\Repositories\Reports\Accounts;

use App\Models\Accounting\Invoice;
use App\Models\Accounting\Receipt;
use App\Models\Admissions\Student;
use Carbon\Carbon;

class AccountsReportsRepository
{
    /**
     * Get all invoices for a given date range
     *
     * @param  string  $from_date Start date for the query
     * @param  string  $to_date End date for the query
     * @param  bool  $executeQuery Whether to execute the query or return the builder
     * @return \Illuminate\Database\Eloquent\Collection | \Illuminate\Database\Eloquent\Builder
     */
    public function RevenueAnalysis($from_date, $to_date, $executeQuery = true)
    {
        $query = Invoice::with('student.user', 'student.program', 'details.fee')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date);

        return $executeQuery ? $query->get() : $query;
    }

    /**
     * Get all invoices and their total amounts for a given date range
     *
     * @param  string  $from_date Start date for the query
     * @param  string  $to_date End date for the query
     * @param  bool  $executeQuery Whether to execute the query or return the builder
     * @return \Illuminate\Database\Eloquent\Collection | \Illuminate\Database\Eloquent\Builder
     */
    public function RevenueAnalysisSummary($from_date, $to_date, $executeQuery = true)
    {
        $query = $this->RevenueAnalysis($from_date, $to_date, false)
            ->withSum('details', 'amount');

        return  $executeQuery ? $query->get() : $query;
    }

    /**
     * Get all transactions for a given date range
     *
     * @param  string  $from_date Start date for the query
     * @param  string  $to_date End date for the query
     * @param  bool  $executeQuery Whether to execute the query or return the builder
     * @return \Illuminate\Database\Eloquent\Collection | \Illuminate\Database\Eloquent\Builder
     */
    public function Transactions($from_date, $to_date, $executeQuery = true)
    {
        $query = Receipt::with('student.user', 'student.program', 'paymentMethod')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date);

        return $executeQuery ? $query->get() : $query;
    }

    public function Aged_Receivables($to_date)
    {
        //        return Student::with('invoices','receipts')
        //            ->get();
        //        $students = Student::with(['invoices.details' => function ($query) {
        //            $query->whereDate('created_at', '<=', Carbon::now());
        //        }, 'receipts'])->get();
        $students = Student::with(['invoices.details' => function ($query) use ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }, 'receipts', 'user', 'program', 'study_mode', 'level'])->get();
        $results = [];

        foreach ($students as $student) {
            // Calculate total invoice amount
            $totalInvoiceAmount = 0;
            foreach ($student->invoices as $invoice) {
                $totalInvoiceAmount += $invoice->details->sum('amount');
            }

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Find the last receipt
            $lastReceipt = $student->receipts->sortByDesc('created_at')->first();

            // Calculate days since last receipt
            //$daysSinceLastReceipt = $lastReceipt ? $lastReceipt->created_at->diffInDays(Carbon::now()) : null;
            $daysSinceLastReceipt = $lastReceipt ? $lastReceipt->created_at->diffInDays(Carbon::now()) : null;

            // Calculate payment percentage
            if (!$totalInvoiceAmount > 0) {
                $totalInvoiceAmount = $totalReceiptAmount;
            }
            $paymentPercentage = $totalReceiptAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;
            $formattedDays = $this->formatDaysSinceLastReceipt($daysSinceLastReceipt);
            // Build the result array
            $results[] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'study_mode' => $student->study_mode->name,
                'level' => $student->level->name,
                'program' => $student->program->name,
                'last_receipt_days' => $daysSinceLastReceipt,
                'payment_percentage' => $paymentPercentage,
                'formatted_days' => $formattedDays,
                'balance' => $balance
            ];
        }
        return $results;

        //return Invoice::with('student.user','student.program','details.fee')->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
    }

    public function StudentList($from_date, $to_date)
    {
        //        return Student::with('invoices','receipts')
        //            ->get();
        //        $students = Student::with(['invoices.details' => function ($query) {
        //            $query->whereDate('created_at', '<=', Carbon::now());
        //        }, 'receipts'])->get();
        $students = Student::with(['invoices.details', 'receipts', 'user', 'program', 'study_mode', 'level'])
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();
        $results = [];

        foreach ($students as $student) {
            // Calculate total invoice amount
            $totalInvoiceAmount = 0;
            foreach ($student->invoices as $invoice) {
                $totalInvoiceAmount += $invoice->details->sum('amount');
            }

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Find the last receipt
            $lastReceipt = $student->receipts->sortByDesc('created_at')->first();

            // Calculate days since last receipt
            $daysSinceLastReceipt = $lastReceipt ? $lastReceipt->created_at->diffInDays(Carbon::now()) : null;

            // Calculate payment percentage
            if (!$totalInvoiceAmount > 0) {
                $totalInvoiceAmount = $totalReceiptAmount;
            }
            $paymentPercentage = $totalReceiptAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;
            $results[] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'study_mode' => $student->study_mode->name,
                'level' => $student->level->name,
                'program' => $student->user->gender,
                'gender' => $student->user->gender,
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }
        return $results;

        //return Invoice::with('student.user','student.program','details.fee')->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
    }
    function formatDaysSinceLastReceipt($days)
    {
        if (!$days) {
            return 'No Receipts';
        }

        $months = (int)floor($days / 30);
        $days %= 30;
        $weeks = (int)floor($days / 7);
        $days %= 7;

        $parts = [];
        if ($months) {
            $parts[] = "$months month" . ($months > 1 ? 's' : '');
        }
        if ($weeks) {
            $parts[] = "$weeks week" . ($weeks > 1 ? 's' : '');
        }
        if ($days) {
            $parts[] = "$days day" . ($days > 1 ? 's' : '');
        }

        return implode(' ', $parts);
    }
}
