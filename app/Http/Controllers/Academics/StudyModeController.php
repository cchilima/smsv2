<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\StudyMode\StudyMode;
use App\Http\Requests\StudyMode\StudyModeUpdate;
use App\Repositories\Academics\StudyModeRepository;
use Illuminate\Http\Request;

class StudyModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $studymode;
    public function __construct(StudyModeRepository $studymode)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->studymode = $studymode;
    }


    public function index()
    {
        $modes['modes'] = $this->studymode->getAll();
        return view('pages.studymodes.index',$modes);
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
    public function store(StudyMode $req)
    {
        //$data = $req->only(['name', 'description']);
        $data = $req->only(['name']);
        $this->studymode->create($data);

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
        $mode['mode'] = $studymode = $this->studymode->find($id);

        return !is_null($studymode ) ? view('pages.studymodes.edit', $mode)
            : Qs::goWithDanger('studymodes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudyModeUpdate $req, $id)
    {

        //$data = $req->only(['name', 'description']);
        $data = $req->only(['name']);
        $this->studymode->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->studymode->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
