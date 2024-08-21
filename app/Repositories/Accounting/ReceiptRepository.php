<?php

namespace App\Repositories\Accounting;

use App\Models\Accounting\{Receipt};

class ReceiptRepository
{
    /**
     * Get all receipts for a given student.
     *
     * @param  int  $studentId The ID of the student.
     * @param  bool  $executeQuery Whether to execute the query or return the builder.
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection
     */
    public function getReceiptsByStudent($studentId, bool $executeQuery = true)
    {
        $query = Receipt::where('student_id', $studentId);
        return $executeQuery ? $query->get() : $query;
    }

    /**
     * Get all non-invoiced receipts for a given student.
     *
     * @param  int  $studentId The ID of the student.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNonInvoicedReceiptsByStudent($studentId)
    {
        return Receipt::where('student_id', $studentId)
            ->whereNull('invoice_id')
            ->where('amount', '>', 0)
            ->get();
    }
}
