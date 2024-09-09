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
use Illuminate\Database\QueryException;
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

        try {
            $data = $request->only(['instructor_id', 'course_id', 'academic_period_id']);
            $data['key'] = rand(2, 23);

            $this->periodClasses->create($data);

            return Qs::jsonStoreOk('Academic period class created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create academic period class');
        }
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
        try {
            $data = $request->only(['instructor_id', 'course_id', 'academic_period_id']);

            $this->periodClasses->update($id, $data);
            return Qs::jsonUpdateOk('Academic period class updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update academic period class: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->periodClasses->find($id)->delete();
            return Qs::goBackWithSuccess('Academic period class deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete an academic period class referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete academic period class: ' . $th->getMessage());
        }
    }
}
