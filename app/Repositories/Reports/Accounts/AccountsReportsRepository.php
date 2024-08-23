<?php

namespace App\Repositories\Reports\Accounts;

use App\Models\Accounting\Invoice;
use App\Models\Accounting\Receipt;
use App\Models\Admissions\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AccountsReportsRepository
{
    /**
     * Get a query for all records in a given date range
     * 
     * @param string $from_date Start date for the query
     * @param string $to_date End date for the query
     * @param \Illuminate\Database\Eloquent\Builder $query The query to run
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryAllFromDateRange($from_date, $to_date, $query): Builder
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
        $query = $this->queryAllFromDateRange($from_date, $to_date, $query);

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
        $query = $this->queryAllFromDateRange($from_date, $to_date, $query);

        return $executeQuery ? $query->get() : $query;
    }

    /**
     * Get all aged receivables for students up to a given date.
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

    /**
     * Retrieve the financial details for students within a specified date range.
     *
     * @param string $from_date The start date for filtering students based on their creation date.
     * @param string $to_date The end date for filtering students based on their creation date.
     * @return array An array of results containing student information, balance, payment percentage,
     *               program, gender, and other related details.
     */

    public function StudentList($from_date, $to_date)
    {
        $query = Student::with([
            'user:id,first_name,last_name,gender',
            'program:id,name',
            'study_mode:id,name',
            'level:id,name'
        ]);

        $students = $this->queryAllFromDateRange($from_date, $to_date, $query)
            ->withCount([
                'invoices as total_invoice_amount' => function ($query) {
                    $query->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
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

            // Build the result array
            $results[] = [
                'id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'study_mode' => $student->study_mode->name,
                'level' => $student->level->name,
                'program' => $student->program->name,  // Corrected the field to 'program->name'
                'gender' => $student->user->gender,
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }

        return $results;
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
