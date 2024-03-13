<?php

namespace App\Repositories\Reports\Accounts;
use App\Models\Accounting\Invoice;

class AccountsReportsRepository
{
    public function RevenueAnalysis($from_date,$to_date)
    {
        return Invoice::with('student.user','student.program','details.fee')->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
    }
    public function RevenueAnalysisSummary($from_date,$to_date)
    {
        return Invoice::with('student.user', 'student.program', 'details.fee')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get()
            ->map(function ($invoice) {
                $totalAmount = $invoice->details->sum('amount');
                return [
                    'invoice' => $invoice,
                    'totalAmount' => $totalAmount,
                ];
            });
        //return Invoice::with('student.user','student.program','details.fee')->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
    }
}

