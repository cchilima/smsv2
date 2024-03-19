<?php

namespace App\Http\Controllers\Academics;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\AcademicPeriods\Period;
use App\Http\Requests\AcademicPeriods\PeriodUpdate;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{

    protected $periods,$periodClasses;

    public function __construct(AcademicPeriodRepository $periods,AcademicPeriodClassRepository $periodClasses)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->periods = $periods;
        $this->periodClasses = $periodClasses;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['open'] = $this->periods->getAllOpenedAc();
        $data['closed'] = $this->periods->getAllClosed();
        $data['periods'] = $this->periods->getAll();
        $data['periodTypes'] = $this->periods->getPeriodTypes();
        $data['studyModes'] = $this->periods->getStudyModes();
        $data['intakes'] = $this->periods->getIntakes();

        return view('pages.academicPeriods.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodTypes = $this->periods->getPeriodTypes();
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();

        return view('pages.academicPeriods.create', compact('periodTypes','studyModes','intakes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Period $request)
    {
        $data = $request->only(['name', 'code', 'ac_start_date', 'ac_end_date', 'period_type_id']);
        $data['ac_start_date'] = date('Y-m-d', strtotime($data['ac_start_date']));
        $data['ac_end_date'] = date('Y-m-d', strtotime($data['ac_end_date']));
        $period = $this->periods->create($data);

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
        $academicPeriod = $this->periods->find($id);

        $periodClasses = $this->periodClasses->getAll();

        $periods = $this->periods->getAPInformation($id);
        $academic= $this->periods->findOne($id);
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();
        $feeInformation = $this->periods->getAPFeeInformation($id);

        return  view('pages.academicPeriods.show', compact('academicPeriod','feeInformation','periods','periodClasses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $academicPeriod = $this->periods->find($id);
        $periodTypes = $this->periods->getPeriodTypes();
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();

        return !is_null($academicPeriod) ? view('pages.academicPeriods.edit', compact('academicPeriod','periodTypes','studyModes','intakes'))
            : Qs::goWithDanger('pages.academicPeriods.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodUpdate $request, string $id)
    {
        $data = $request->only(['name', 'code', 'ac_start_date', 'ac_end_date', 'period_type_id']);
        $data['ac_start_date'] = date('Y-m-d', strtotime($data['ac_start_date']));
        $data['ac_end_date'] = date('Y-m-d', strtotime($data['ac_end_date']));
        $this->periods->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->periods->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
