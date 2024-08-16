<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Qualifications\Qualification;
use App\Http\Requests\Qualifications\QualificationUpdate;
use App\Repositories\Academics\QualificationsRepository;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    protected $qualifications;
    public function __construct(QualificationsRepository $qualifications)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->qualifications = $qualifications;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Qualification $req)
    {
        $data = $req->only(['name']);
        $data['slug'] = $data['name'];
        $this->qualifications->create($data);

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
        $data['qualification'] = $qualifications = $this->qualifications->find($id);

        return !is_null($qualifications) ? view('pages.qualifications.edit', $data)
            : Qs::goWithDanger('pages.qualifications.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QualificationUpdate $req, string $id)
    {
        $data = $req->only(['name']);
        $data['slug'] = $data['name'];
        $this->qualifications->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->qualifications->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
