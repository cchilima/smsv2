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
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $programs,$depart,$qualification,$programCourse,$levels,$courses;
    public function __construct(ProgramsRepository $programs,DepartmentsRepository $depat,
                                QualificationsRepository $qualification,ProgramCoursesRepository $programCourse,
                                CourseRepository $courses,CourseLevelsRepository $levels
    )
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->programs = $programs;
        $this->depart = $depat;
        $this->qualification = $qualification;
        $this->programCourse = $programCourse;
        $this->levels = $levels;
        $this->courses = $courses;
    }
    public function index()
    {
        $program['programs'] = $this->programs->getAll();
        $program['departments'] = $this->depart->getAll();
        $program['qualifications'] = $this->qualification->getAll();
        return view('pages.programs.index',$program);
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
    public function store(Program $req)
    {
        $data = $req->only(['code', 'name','department_id','qualification_id','description']);
        $data['slug'] = $data['code'];
        $this->programs->create($data);

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = Qs::decodeHash($id);
        $myprogram['withCourseLevels'] =$this->programs->findOneP($id);
        //dd($myprogram['withCourseLevels']);
        $myprogram['programs'] = $someprograms = $this->programs->find($id);
        $myprogram['program'] = $someprograms = $this->programs->findOne($id);
       // dd($myprogram['withCourseLevels']);
        $myprogram['levels'] = $this->levels->getAll();
        $myprogram['newcourses'] = $this->courses->getAll();
        $myprogram['pcourses'] = [];

        return !is_null($someprograms) ? view('pages.programs.show',$myprogram)
            : Qs::goWithDanger('pages.programs.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program['program'] = $programs = $this->programs->find($id);
        $program['departments'] = $this->depart->getAll();
        $program['qualifications'] = $this->qualification->getAll();
        return !is_null($programs) ? view('pages.programs.edit', $program)
            : Qs::goWithDanger('pages.programs.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProgramUpdate $req, string $id)
    {
        $data = $req->only(['code', 'name','department_id','qualification_id','description']);
        $this->programs->update($id,$data);

        return Qs::jsonStoreOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->programs->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
