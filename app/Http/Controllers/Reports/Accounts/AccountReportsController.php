<?php

namespace App\Http\Controllers\Reports\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Http\Request;

class AccountReportsController extends Controller
{
    protected $revenue_analysis;
    public function __construct(AccountsReportsRepository $revenue_analysis)
    {
        //$this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);
        $this->revenue_analysis = $revenue_analysis;
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
    public function RevenueAnalysis(Request $request){

        if (isset($request['from_date']) && !$request['from_date'] == '' && isset($request['to_date']) && !$request['to_date'] == '') {
//            $from_date = $request['from_date'];
//            $to_date = $request['to_date'];
//            $from_date = date('Y-m-d', strtotime($request['from_date']));
//            $to_date = date('Y-m-d', strtotime($request['to_date']));
            $revenue['revenue_analysis'] = $this->revenue_analysis->RevenueAnalysis(date('Y-m-d', strtotime($request['from_date'])),date('Y-m-d', strtotime($request['to_date'])));
            return view('pages.reports.accounts.revenue_analysis',$revenue);
        } else {
            return view('pages.reports.accounts.revenue_analysis');
        }
    }

    public function invoices(Request $request){

        if (isset($request['from_date']) && !$request['from_date'] == '' && isset($request['to_date']) && !$request['to_date'] == '') {
            $revenue['revenue_analysis'] = $this->revenue_analysis->RevenueAnalysisSummary(date('Y-m-d', strtotime($request['from_date'])),date('Y-m-d', strtotime($request['to_date'])));

           // dd($revenue);
            return view('pages.reports.accounts.invoices',$revenue);
        } else {
            return view('pages.reports.accounts.invoices');
        }
        //return view('pages.reports.accounts.invoices');
    }
    public function Transactions(){
        return view('pages.reports.accounts.transactions');
    }
    public function FailedPayments(){
        return view('pages.reports.accounts.failed_transactions');
    }
    public function AgedReceivables(){
        return view('pages.reports.accounts.aged_receivables');
    }
    public function CreditNotes(){
        return view('pages.reports.accounts.credit_notes');
    }
    public function StudentList(){
        return view('pages.reports.accounts.student_list');
    }
}
