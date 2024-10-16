<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\Qs;
use App\Http\Middleware\Custom\TeamSAT;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Accounting\QuotationRepository;

class QuotationController extends Controller
{

    protected $quotationRepo;

    public function __construct(QuotationRepository $quotationRepo)
    {
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);
        $this->quotationRepo = $quotationRepo;
    }

    /**
     * Invoice student for a specific academic period.
     */
    public function Quotation(Request $request)
    {
        try {
            if ($this->quotationRepo->getStudentQuotation($request->academic_period, $request->student_id)) {
                return Qs::jsonStoreOk('Quotation generated successfully. Pay now to register and get an invoice for the academic period.');
            } else {
                return Qs::jsonError('Failed to generate quotaion');
            }
        } catch (\Throwable $th) {
            Qs::jsonError('Failed to generate quotation : ' . $th->getMessage());
        }
    }
}
