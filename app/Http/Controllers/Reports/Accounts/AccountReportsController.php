<?php

namespace App\Http\Controllers\Reports\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Middleware\Custom\TeamSAT;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Accounting\PaymentMethodRepository;
use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Http\Request;

class AccountReportsController extends Controller
{
    protected $revenue_analysis, $payment_methods;
    public function __construct(AccountsReportsRepository $revenue_analysis, PaymentMethodRepository $payment_methods)
    {
        //$this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);
        $this->middleware(TeamSAT::class, ['except' => ['destroy',]]);
        $this->revenue_analysis = $revenue_analysis;
        $this->payment_methods = $payment_methods;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function RevenueAnalysis(Request $request)
    {
        $datesSet = !empty($request['from_date']) && !empty($request['to_date']);
        $pageTitle = 'Revenue Analysis Report';

        if ($datesSet) {
            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $fromDateFormatted = date('d M Y', strtotime($fromDate));
            $toDateFormatted = date('d M Y', strtotime($toDate));

            $pageTitle = 'Revenue Analysis Report (' . $fromDateFormatted . ' to ' . $toDateFormatted . ')';

            return view('pages.reports.accounts.revenue_analysis', compact('datesSet', 'fromDate', 'toDate', 'pageTitle'));
        }

        return view('pages.reports.accounts.revenue_analysis', compact('datesSet', 'pageTitle'));
    }

    public function invoices(Request $request)
    {

        if (isset($request['from_date']) && !$request['from_date'] == '' && isset($request['to_date']) && !$request['to_date'] == '') {
            $revenue['revenue_analysis'] = $this->revenue_analysis->RevenueAnalysisSummary(date('Y-m-d', strtotime($request['from_date'])), date('Y-m-d', strtotime($request['to_date'])));

            // dd($revenue);
            return view('pages.reports.accounts.invoices', $revenue);
        } else {
            return view('pages.reports.accounts.invoices');
        }
        //return view('pages.reports.accounts.invoices');
    }
    public function Transactions(Request $request)
    {

        if (isset($request['from_date']) && !$request['from_date'] == '' && isset($request['to_date']) && !$request['to_date'] == '' && isset($request['payment_method']) && !$request['payment_method'] == '') {
            $revenue['transactions'] = $this->revenue_analysis->Transactions(date('Y-m-d', strtotime($request['from_date'])), date('Y-m-d', strtotime($request['to_date'])), $request['payment_method']);
            $revenue['payment_methods'] = $this->payment_methods->getAll();
            return view('pages.reports.accounts.transactions', $revenue);
        } else {
            $payment_methods = $this->payment_methods->getAll();
            //dd($payment_methods);
            return view('pages.reports.accounts.transactions', compact('payment_methods'));
            // return view('pages.reports.accounts.invoices');
        }
    }
    public function FailedPayments()
    {
        return view('pages.reports.accounts.failed_transactions');
    }
    public function AgedReceivables(Request $request)
    {

        if (isset($request['to_date']) && !$request['to_date'] == '') {

            $revenue['age_analysis'] = $this->revenue_analysis->Aged_Receivables(date('Y-m-d', strtotime($request['to_date'])));
            //dd($revenue['transactions']);
            return view('pages.reports.accounts.aged_receivables', $revenue);
        } else {
            return view('pages.reports.accounts.aged_receivables');
        }
        //return view('pages.reports.accounts.aged_receivables');
    }
    public function CreditNotes()
    {
        return view('pages.reports.accounts.credit_notes');
    }
    public function StudentList(Request $request)
    {

        if (isset($request['from_date']) && !$request['from_date'] == '' && isset($request['to_date']) && !$request['to_date'] == '') {

            $revenue['student_list'] = $this->revenue_analysis->StudentList(date('Y-m-d', strtotime($request['from_date'])), date('Y-m-d', strtotime($request['to_date'])));
            /// dd($revenue['student_list']);
            return view('pages.reports.accounts.student_list', $revenue);
        } else {
            return view('pages.reports.accounts.student_list');
        }

        // return view('pages.reports.accounts.student_list');
    }
}
