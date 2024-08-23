<?php

namespace App\Repositories\Reports\Accounts;

use App\Models\Accounting\Invoice;
use App\Models\Accounting\Receipt;
use App\Models\Admissions\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountsReportsRepository
{
    /**
     * Get all the records from a given date range
     * 
     * @param string $from_date Start date for the query
     * @param string $to_date End date for the query
     * @param \Illuminate\Database\Eloquent\Builder $query The query to run
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getAllFromDateRange($from_date, $to_date, $query)
    {
        if ($from_date) $query = $query->whereDate('created_at', '>=', $from_date);
        if ($to_date) $query = $query->whereDate('created_at', '<=', $to_date);

        return $query;
    }

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
        $query = Invoice::with('student.user', 'student.program', 'details.fee');
        $query = $this->getAllFromDateRange($from_date, $to_date, $query);

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
        $query = Receipt::with('student.user', 'student.program', 'paymentMethod');
        $query = $this->getAllFromDateRange($from_date, $to_date, $query);

        return $executeQuery ? $query->get() : $query;
    }

    /**
     * Get all aged receivables students up to a given date.
     *
     * This method retrieves all students along with their associated invoices, receipts,
     * and other related data. It calculates the total invoice amount, total receipt amount,
     * and balance for each student. It also determines the number of days since the last receipt
     * and calculates the payment percentage based on the total invoice and receipt amounts.
     *
     * @param string $to_date The date up to which invoices should be retrieved.
     * @return array An array of results containing student information, balance, payment percentage,
     *               days since the last receipt, and formatted days.
     */
    public function Aged_Receivables($to_date)
    {
        $students = Student::with([
            'user:id,first_name,last_name',
            'program:id,name',
            'study_mode:id,name',
            'level:id,name'
        ])->withCount([
            'invoices as total_invoice_amount' => function ($query) use ($to_date) {
                $query->whereDate('invoices.created_at', '<=', $to_date)
                    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->select(DB::raw('SUM(invoice_details.amount)'));
            },
            'receipts as total_receipt_amount' => function ($query) {
                $query->select(DB::raw('SUM(amount)'));
            },
            'receipts as last_receipt_at' => function ($query) {
                $query->select(DB::raw('MAX(receipts.created_at)'));
            }
        ])->get();

        $results = [];

        foreach ($students as $student) {
            $totalInvoiceAmount = $student->total_invoice_amount ?? 0;
            $totalReceiptAmount = $student->total_receipt_amount ?? 0;

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Calculate days since last receipt
            $daysSinceLastReceipt = $student->last_receipt_at ? Carbon::parse($student->last_receipt_at)->diffInDays(Carbon::now()) : null;

            // Calculate payment percentage
            $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

            $formattedDays = $this->formatDaysSinceLastReceipt($daysSinceLastReceipt);

            // Build the result array
            $results[] = [
                'id' => $student->id,
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
    }

    public function StudentList($from_date, $to_date)
    {
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
