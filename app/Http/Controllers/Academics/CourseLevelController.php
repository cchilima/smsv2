<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\CourseLevels\CourseLevels;
use App\Http\Requests\CourseLevels\CourseLevelsUpdate;
use App\Http\Requests\Courses\CoursesUpdate;
use App\Repositories\Academics\CourseLevelsRepository;
use Illuminate\Http\Request;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $courselevels;
    public function __construct(CourseLevelsRepository $courselevels)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->courselevels = $courselevels;
    }
    public function index()
    {
        return view('pages.levels.index');
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
    public function store(CourseLevels $req)
    {
        $data = $req->only(['name']);
        $this->courselevels->create($data);

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
        $data['levels'] = $courselevels = $this->courselevels->find($id);

        return !is_null($courselevels) ? view('pages.levels.edit', $data)
            : Qs::goWithDanger('pages.levels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseLevelsUpdate $req, string $id)
    {
        $data = $req->only(['name']);
        $this->courselevels->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->courselevels->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
