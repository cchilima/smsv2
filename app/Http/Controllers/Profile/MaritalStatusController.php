<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\MaritalStatuses\MaritalStatus;
use App\Http\Requests\MaritalStatuses\MaritalStatusUpdate;
use App\Repositories\Profile\MaritalStatusRepository;
use Illuminate\Http\Request;

class MaritalStatusController extends Controller
{

    protected $maritalStatuses;

    public function __construct(MaritalStatusRepository $maritalStatuses)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->maritalStatuses = $maritalStatuses;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.maritalStatuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.maritalStatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaritalStatus $request)
    {
        $data = $request->only(['status', 'description']);

        $maritalStatus = $this->maritalStatuses->create($data);

        if ($maritalStatus) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError(__('msg.create_failed'));
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
        $maritalStatus = $this->maritalStatuses->find($id);

        return !is_null($maritalStatus) ? view('pages.maritalStatuses.edit', compact('maritalStatus'))
            : Qs::goWithDanger('pages.maritalStatuses.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(MaritalStatusUpdate $request, string $id)
    {
        $data = $request->only(['status', 'description']);
        $this->maritalStatuses->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->maritalStatuses->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
