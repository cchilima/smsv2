<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\InvoicesExport;
use App\Helpers\Qs;
use App\Http\Middleware\Custom\TeamSAT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Admissions\Student;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Academics\StudentRegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Elibyy\TCPDF\Facades\TCPDF;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{

    protected $invoiceRepo;
    protected $enrollmentRepo;
    protected $studentRepo;

    public function __construct(InvoiceRepository $invoiceRepo, EnrollmentRepository $enrollmentRepo, StudentRegistrationRepository $studentRepo)
    {
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);
        $this->invoiceRepo = $invoiceRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->studentRepo = $studentRepo;
    }

    /**
     * Batch invoice students for a specific academic period.
     */
    public function batchInvoicing(Request $request)
    {
        try {

            $this->invoiceRepo->invoiceStudents($request->academic_period);
            return Qs::jsonStoreOk('Batch invoicing successful');

        } catch (\Throwable $th) {
            return Qs::jsonError('Batch invoicing failed: ' . $th->getMessage());
        }
    }


    /**
     * Invoice student for a specific academic period.
     */
    public function Invoice(Request $request)
    {
        try {

            $this->invoiceRepo->invoiceStudent($request->academic_period, $request->student_id);

             // Get courses student can register for
             $courseToRegister = $this->studentRepo->getAll($request->student_id);

             // Register and enroll student in the above courses.
             $this->enrollmentRepo->create($courseToRegister, $request->student_id);

            return Qs::jsonStoreOk('Student invoiced and enrolled successfully');

        } catch (\Throwable $th) {
            Qs::jsonError('Failed to invoice student: ' . $th->getMessage());
        }
    }

    public function customInvoice(Request $request)
    {
        try {
            $this->invoiceRepo->customInvoiceStudent(
                $request->amount,
                $request->fee_id,
                $request->student_id
            );

            return Qs::jsonStoreOk('Student invoiced successfully');
        } catch (\Throwable $th) {
            Qs::jsonError('Failed to invoice student: ' . $th->getMessage());
        }
    }

    public function downloadInvoice(Request $request, Invoice $invoice)
    {
        try {
            $student = $invoice->student;
            $fileName = $student->id . '-invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf';

            $pdf = Pdf::loadView('templates.pdf.invoice', compact('invoice', 'student'));

            return $pdf->download($fileName);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to download invoice: ' . $th->getMessage());
        }
    }

    public function exportInvoices(Request $request, Student $student)
    {
        try {
            $fileName = $student->id . '-invoices-' . now()->format('d-m-Y-His') . '.xlsx';
            $export = new InvoicesExport($student);

            return Excel::download($export, $fileName);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to export invoices: ' . $th->getMessage());
        }
    }
}
