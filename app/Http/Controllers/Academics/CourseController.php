<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Courses\Courses;
use App\Http\Requests\Courses\CoursesUpdate;
use App\Repositories\Academics\CourseRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $courses;
    public function __construct(CourseRepository $courses)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->courses = $courses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Courses $req)
    {
        $data = $req->only(['code', 'name']);
        $this->courses->create($data);

        return Qs::jsonStoreOk();
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
        $courses['course'] = $courses = $this->courses->find($id);

        return !is_null($courses) ? view('pages.courses.edit', $courses)
            : Qs::goWithDanger('pages.courses.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoursesUpdate $req, string $id)
    {
        $data = $req->only(['code', 'name']);
        $this->courses->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->courses->find($id)->delete();
        return Qs::goBackWithSuccess('Record deleted successfully');;
    }
}
