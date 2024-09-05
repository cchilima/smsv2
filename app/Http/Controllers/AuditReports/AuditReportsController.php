<?php

namespace App\Http\Controllers\AuditReports;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Reports\Audits\AuditReportsRepository;
use Illuminate\Http\Request;

class AuditReportsController extends Controller
{
    protected $auditReportsRepository;

    public function __construct(AuditReportsRepository $auditReportsRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->auditReportsRepository = $auditReportsRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = $this->auditReportsRepository->getAll();
        //dd($audits);
        return view('pages.auditReports.index', compact('audits'));
    }
}
