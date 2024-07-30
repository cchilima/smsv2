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

        try {

            $collected = $this->statementRepo->collectPayment($request->amount, $request->academic_period, $request->student_id, $request->payment_method_id);


            if ($collected) {
                return Qs::jsonStoreOk();
            } else {
                dd($collected);
                return Qs::json('failed to collect payment', false);
            }
        } catch (\Exception $e) {

            // Log the error or handle it accordingly
            return Qs::json('failed to collect payment', false);
        }
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

    public function downloadStatement(Request $request, Invoice $invoice)
    {
        $fileType = $request['file-type'];
        $student = $invoice->student;
        $fileName = $student->id . '-statement-' . $invoice->created_at->format('d-m-Y') . '.pdf';

        $pdf = Pdf::loadView('templates.pdf.statement', compact('student', 'invoice', 'fileName'));

        return $pdf->download($fileName);
    }

    public function exportStatements(Request $request, Student $student)
    {
        $fileName = $student->id . '-statements-' . now()->format('d-m-Y-His') . '.xlsx';
        $export = new StatementsExport($student);

        return Excel::download($export, $fileName);
    }
}
