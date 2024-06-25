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
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->auditReportsRepository = $auditReportsRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = $this->auditReportsRepository->getAll();
        //dd($audits);
        return view('pages.auditReports.index',compact('audits'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
