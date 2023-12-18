<?php

namespace App\Http\Controllers\Academics;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\AcademicPeriods\Period;
use App\Http\Requests\AcademicPeriods\PeriodUpdate;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{

    protected $periods;
    
    public function __construct(AcademicPeriodRepository $periods)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->periods = $periods;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = $this->periods->getAll();
        $periodTypes = $this->periods->getPeriodTypes();
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();
    
        return view('pages.academicPeriods.index', compact('periods', 'periodTypes', 'studyModes', 'intakes'));
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
        $data = $request->only(['code', 'registration_date', 'late_registration_date', 'ac_start_date', 'ace_end_date', 'period_type_id', 'academic_period_intake_id', 'type', 'study_mode_id']);
        
        $period = $this->periods->create($data);

        if ($period) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError(__('msg.create_failed'));
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
        $period = $this->periods->find($id);
        $periodTypes = $this->periods->getPeriodTypes();
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();
    
        return !is_null($fee) ? view('pages.academicPeriods.edit', compact('period','periodTypes','studyModes','intakes'))
            : Qs::goWithDanger('pages.academicPeriods.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only(['code', 'registration_date', 'late_registration_date', 'ac_start_date', 'ace_end_date', 'period_type_id', 'academic_period_intake_id', 'type', 'study_mode_id']);
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
