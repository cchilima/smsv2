<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Intakes\Intake;
use App\Http\Requests\Intakes\IntakeUpdate;
use App\Repositories\Academics\IntakesRepository;
use Illuminate\Http\Request;

class IntakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $intakes;
    public function __construct(IntakesRepository $intakes)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->intakes = $intakes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Intake $req)
    {

        $data = $req->only(['name']);
        $this->intakes->create($data);

        return Qs::jsonStoreOk();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['intake'] = $intake = $this->intakes->find($id);

        return !is_null($intake) ? view('pages.intakes.edit', $data)
            : Qs::goWithDanger('pages.intakes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IntakeUpdate $req, string $id)
    {
        $data = $req->only(['name']);
        $this->intakes->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->intakes->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
