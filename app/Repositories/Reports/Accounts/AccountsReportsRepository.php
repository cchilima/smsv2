<?php

namespace App\Repositories\Reports\Accounts;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\Receipt;

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
    public function Transactions($from_date,$to_date,$method)
    {
        return Receipt::with('student.user', 'student.program', 'paymentMethod')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->where('payment_method_id', '=', $method)
            ->get();
        //return Invoice::with('student.user','student.program','details.fee')->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
    }
}

