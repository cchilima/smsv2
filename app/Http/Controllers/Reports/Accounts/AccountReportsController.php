<?php

namespace App\Http\Controllers\Reports\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use Illuminate\Http\Request;

class AccountReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);
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
    public function RevenueAnalysis(){
        return view('pages.reports.accounts.revenue_analysis');
    }

    public function invoices(){
        return view('pages.reports.accounts.invoices');
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
