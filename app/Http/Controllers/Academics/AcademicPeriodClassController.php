<?php

namespace App\Http\Controllers\Academics;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\AcademicPeriodClasses\PeriodClass;
use App\Http\Requests\AcademicPeriodClasses\PeriodClassUpdate;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Http\Request;

class AcademicPeriodClassController extends Controller
{

    protected $periodClasses, $academicPeriodRepository;

    public function __construct(AcademicPeriodClassRepository $periodClasses, AcademicPeriodRepository $academicPeriodRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->periodClasses = $periodClasses;
        $this->academicPeriodRepository = $academicPeriodRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodClasses = $this->periodClasses->getAll();

        $courses = $this->periodClasses->getCourses();
        $instructors = $this->periodClasses->getInstructors();
        $academicPeriods = $this->academicPeriodRepository->getAllOpenedAc();

        return view('pages.academicPeriodClasses.index', compact('periodClasses', 'courses', 'instructors', 'academicPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $courses = $this->periodClasses->getCourses();
        $instructors = $this->periodClasses->getInstructors();
        $academicPeriods = $this->periodClasses->getAcademicPeriods();

        return view('pages.academicPeriodClasses.create', compact('courses', 'instructors', 'academicPeriods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeriodClass $request)
    {

        $data = $request->only(['instructor_id', 'course_id', 'academic_period_id']);
        $data['key'] = rand(2, 23);


        $periodClass = $this->periodClasses->create($data);

        if ($periodClass) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false, 'error to create message message');
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
        $periodClass = $this->periodClasses->find($id);

        $courses = $this->periodClasses->getCourses();
        $instructors = $this->periodClasses->getInstructors();

        return !is_null($instructors) ? view('pages.academicPeriodClasses.edit', compact('periodClass', 'courses', 'instructors'))
            : Qs::goWithDanger('pages.academicPeriodClasses.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only(['instructor_id', 'course_id', 'academic_period_id']);
        $this->periodClasses->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->periodClasses->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
