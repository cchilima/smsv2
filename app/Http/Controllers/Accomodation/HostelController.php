<?php

namespace App\Http\Controllers\Accomodation;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Accomodation\Hostel;
use App\Http\Requests\Accomodation\HostelUpdate;
use App\Repositories\Accommodation\HostelRepository;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $hostel_repository;

    public function __construct(HostelRepository $hostel_repository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->hostel_repository = $hostel_repository;
    }
    public function index()
    {
        return view('pages.hostels.index');
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
    public function store(Hostel $request)
    {
        $data = $request->only(['hostel_name', 'location']);

        $hostel = $this->hostel_repository->create($data);

        if ($hostel) {
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
        $hostel = $this->hostel_repository->find($id);

        return !is_null($hostel) ? view('pages.hostels.edit', compact('hostel'))
            : Qs::goWithDanger('pages.hostels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HostelUpdate $request, string $id)
    {
        $data = $request->only(['hostel_name', 'location']);
        $this->hostel_repository->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->hostel_repository->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
