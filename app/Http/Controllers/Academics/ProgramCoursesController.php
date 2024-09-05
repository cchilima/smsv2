<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\ProgramCourses\ProgramCourse;
use App\Repositories\Academics\ProgramCoursesRepository;
use Illuminate\Http\Request;

class ProgramCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $programCoursesRepo;
    public function __construct(ProgramCoursesRepository $programCoursesRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);


        $this->programCoursesRepo = $programCoursesRepo;
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
    public function store(ProgramCourse $req)
    {
        try {
            $data = $req->only(['course_id', 'program_id', 'level_id']);

            foreach ($data['course_id'] as $courseID) {
                $this->programCoursesRepo->create([
                    'course_id' => $courseID,
                    'program_id' => $data['program_id'],
                    'course_level_id' => $data['level_id'],
                ]);
            }

            return Qs::jsonStoreOk('Program course created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create program course: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $program, string $level, string $course)
    {
        try {
            $this->programCoursesRepo->findProgramlevelCoursesDelete($program, $level, $course)->delete();
            return Qs::goBackWithSuccess('Program course deleted successfully');;
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete program course: ' . $th->getMessage());
        }
    }
}
