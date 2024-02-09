<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Accounting\InvoiceRepository;


class InvoiceController extends Controller
{

    protected $invoiceRepo;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
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

    /**
     * Batch invoice students for a specific academic period.
     */
    public function batchInvoicing(Request $request)
    {
        try {

            $batchInvoiced = $this->invoiceRepo->invoiceStudents($request->academic_period);

            return Qs::jsonStoreOk();

        } catch (\Exception $e) {

            // Log the error or handle it accordingly
            return Qs::json(false,'failed to invoice batch');
        }
    }


    /**
     * Invoice student for a specific academic period.
     */
    public function Invoice(Request $request)
    {

        try {

            $student_invoiced = $this->invoiceRepo->invoiceStudent($request->academic_period, $request->student_id);

            return $student_invoiced ? Qs::jsonStoreOk() : Qs::json(false, 'Failed to invoice student.');


        } catch (\Exception $e) {

            // Log the error or handle it accordingly

        }
    }

    public function customInvoice(Request $request)
    {
        try {

        // custom invoice student
        $student_invoiced = $this->invoiceRepo->customInvoiceStudent($request->amount, $request->fee_id, $request->student_id);

        // give user feedback
        return $student_invoiced ? Qs::jsonStoreOk() : Qs::json(false, 'Failed to invoice student.');

        } catch (\Exception $e) {
            //throw $th;
        }
      

    }
}
