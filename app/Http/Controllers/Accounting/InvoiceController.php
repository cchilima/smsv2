<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\InvoicesExport;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Admissions\Student;
use App\Repositories\Accounting\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Elibyy\TCPDF\Facades\TCPDF;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Maatwebsite\Excel\Facades\Excel;

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

            if ($batchInvoiced) {

                return Qs::jsonStoreOk();
            } else {
                return Qs::json(false, 'failed to invoice batch');
            }
        } catch (\Exception $e) {

            // Log the error or handle it accordingly
            return Qs::json(false, 'failed to invoice batch');
        }
    }


    /**
     * Invoice student for a specific academic period.
     */
    public function Invoice(Request $request)
    {

        try {

            $student_invoiced = $this->invoiceRepo->invoiceStudent($request->academic_period, $request->student_id);

            return $student_invoiced ? Qs::jsonStoreOk() : Qs::json('Failed to invoice student.', false);
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

    public function downloadInvoice(Request $request, Invoice $invoice)
    {
        $student = $invoice->student;
        $fileName = $student->id . '-invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf';

        $pdf = Pdf::loadView('templates.pdf.invoice', compact('invoice', 'student'));

        return $pdf->download($fileName);
    }

    public function exportInvoices(Request $request, Student $student)
    {
        $fileName = $student->id . '-invoices-' . now()->format('d-m-Y-His') . '.xlsx';
        $export = new InvoicesExport($student);


        return Excel::download($export, $fileName);
    }
}
