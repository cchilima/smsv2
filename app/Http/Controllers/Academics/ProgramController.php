<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Programs\Program;
use App\Http\Requests\Programs\ProgramUpdate;
use App\Repositories\Academics\CourseLevelsRepository;
use App\Repositories\Academics\CourseRepository;
use App\Repositories\Academics\DepartmentsRepository;
use App\Repositories\Academics\ProgramCoursesRepository;
use App\Repositories\Academics\ProgramsRepository;
use App\Repositories\Academics\QualificationsRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $programs, $depart, $qualification, $programCourse, $levels, $courses;
    public function __construct(
        ProgramsRepository $programs,
        DepartmentsRepository $depat,
        QualificationsRepository $qualification,
        ProgramCoursesRepository $programCourse,
        CourseRepository $courses,
        CourseLevelsRepository $levels
    ) {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->programs = $programs;
        $this->depart = $depat;
        $this->qualification = $qualification;
        $this->programCourse = $programCourse;
        $this->levels = $levels;
        $this->courses = $courses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Program $req)
    {
        try {
            $data = $req->only(['code', 'name', 'department_id', 'qualification_id', 'description']);
            $data['slug'] = $data['code'];
            $this->programs->create($data);

            return Qs::jsonStoreOk('Program created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create program: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program['program'] = $this->programs->findOne($id);
        $program['departments'] = $this->depart->getAll();
        $program['qualifications'] = $this->qualification->getAll();

        return !is_null($program['program']) ? view('pages.programs.edit', $program)
            : Qs::goWithDanger('programs.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProgramUpdate $req, string $id)
    {
        try {
            $data = $req->only(['code', 'name', 'department_id', 'qualification_id', 'description']);
            $this->programs->update($id, $data);

            return Qs::jsonUpdateOk('Program updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update program: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->programs->findOne($id)->delete();
            return Qs::goBackWithSuccess('Program deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete program referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete program: ' . $th->getMessage());
        }
    }
}
