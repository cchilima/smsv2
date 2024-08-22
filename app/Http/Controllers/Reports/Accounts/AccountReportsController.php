<?php

namespace App\Http\Controllers\Reports\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Middleware\Custom\TeamSAT;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Accounting\PaymentMethodRepository;
use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function RevenueAnalysis(): View
    {
        return $this->renderAccountingReportView('Revenue Analysis Report', 'pages.reports.accounts.revenue_analysis');
    }

    public function invoices(): View
    {
        return $this->renderAccountingReportView('Invoices Summary Report', 'pages.reports.accounts.invoices');
    }

    public function Transactions(): View
    {
        return $this->renderAccountingReportView('Transactions Report', 'pages.reports.accounts.transactions');
    }

    /**
     * Render an accounting report view
     * 
     * @param string $pageTitle The title of the page
     * @param string $viewPath The path to the Blade view
     * @param array $data The data to pass to the view
     * @return \Illuminate\Contracts\View\View
     */
    private function renderAccountingReportView($pageTitle = "Report", $viewPath, $data = [])
    {
        $datesSet = !empty(request('from_date') && !empty(request('to_date')));

        if ($datesSet) {
            $data['datesSet'] = $datesSet;

            $data['fromDate'] = Carbon::parse(request('from_date'))->format('Y-m-d');
            $data['toDate'] = Carbon::parse(request('to_date'))->format('Y-m-d');

            $data['pageTitle'] = sprintf(
                '%s (%s to %s)',
                $pageTitle,
                Carbon::parse($data['fromDate'])->format('d M Y'),
                Carbon::parse($data['toDate'])->format('d M Y')
            );

            return view($viewPath, $data);
        }

        return view($viewPath, compact('datesSet', 'pageTitle'));
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
