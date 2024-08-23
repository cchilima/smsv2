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

    public function AgedReceivables(): View
    {
        return $this->renderAccountingReportView('Aged Receivables Report', 'pages.reports.accounts.aged_receivables');
    }

    public function StudentList(): View
    {
        return $this->renderAccountingReportView('Student List Report', 'pages.reports.accounts.student_list');
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
        $datesSet = !empty(request('from_date')) || !empty(request('to_date'));

        if ($datesSet) {
            $fromDate = request('from_date');
            $toDate = request('to_date');

            $data['datesSet'] = $datesSet;

            // If dates are not set, set them to empty strings
            $data['fromDate'] = $fromDate ? Carbon::parse($fromDate)->format('Y-m-d') : '';
            $data['toDate'] = $toDate ? Carbon::parse($toDate)->format('Y-m-d') : '';

            // Create and format the page title
            $data['pageTitle'] = sprintf(
                '%s (%s%s%s)',
                $pageTitle,
                $fromDate ? Carbon::parse($fromDate)->format('d M Y') : '',
                $fromDate && $toDate ? ' to ' : ($fromDate ? ' onwards' : 'Up to '),
                $toDate ? Carbon::parse($toDate)->format('d M Y') : ''
            );

            return view($viewPath, $data);
        }

        return view($viewPath, compact('datesSet', 'pageTitle'));
    }

    public function FailedPayments()
    {
        return view('pages.reports.accounts.failed_transactions');
    }

    public function CreditNotes()
    {
        return view('pages.reports.accounts.credit_notes');
    }
}
