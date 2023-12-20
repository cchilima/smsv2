<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\AcademicPeriodFees\AcdemicPeriodFees;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Http\Request;

class APFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $periods;

    public function __construct(AcademicPeriodRepository $periods)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->periods = $periods;
    }
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
    public function store(AcdemicPeriodFees $request)
    {
        $data = $request->only(['amount', 'fee_id', 'academic_period_id']);
        $period = $this->periods->APFeeCreate($data);

        if ($period) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'failed to create message');
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
        $id = Qs::decodeHash($id);
        $fees = $this->periods->getFees();
        $feeInformation = $this->periods->getOneAPFeeInformation($id);

        return view('pages.academicPeriodInformation.edit_feesAc', compact('feeInformation','fees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only(['amount', 'fee_id']);
        $period = $this->periods->APFeeUpdate($id,$data);

        if ($period) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'failed to create message');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
