<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\StatementsExport;
use App\Helpers\Qs;
use App\Http\Middleware\Custom\TeamSAT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Admissions\Student;
use App\Repositories\Accounting\StatementRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Elibyy\TCPDF\Facades\TCPDF;
use Maatwebsite\Excel\Facades\Excel;

class StatementController extends Controller
{

    protected $statementRepo;

    public function __construct(StatementRepository $statementRepo)
    {
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);
        $this->statementRepo = $statementRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->statementRepo->collectPayment(
                $request->amount,
                $request->academic_period,
                $request->student_id,
                $request->payment_method_id
            );

            return Qs::jsonStoreOk('Payment collected successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to collect payment: ' . $th->getMessage());
        }
    }

    public function downloadStatement(Request $request, Invoice $invoice)
    {
        try {
            $fileType = $request['file-type'];
            $student = $invoice->student;
            $fileName = $student->id . '-statement-' . $invoice->created_at->format('d-m-Y') . '.pdf';

            $pdf = Pdf::loadView('templates.pdf.statement', compact('student', 'invoice', 'fileName'));

            return $pdf->download($fileName);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to download statement: ' . $th->getMessage());
        }
    }

    public function exportStatements(Request $request, Student $student)
    {
        try {
            $fileName = $student->id . '-statements-' . now()->format('d-m-Y-His') . '.xlsx';
            $export = new StatementsExport($student);

            return Excel::download($export, $fileName);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to export statements: ' . $th->getMessage());
        }
    }
}
