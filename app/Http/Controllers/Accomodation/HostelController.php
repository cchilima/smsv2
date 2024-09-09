<?php

namespace App\Http\Controllers\Accomodation;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Accomodation\Hostel;
use App\Http\Requests\Accomodation\HostelUpdate;
use App\Repositories\Accommodation\HostelRepository;
use Illuminate\Database\QueryException;
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
     * Store a newly created resource in storage.
     */
    public function store(Hostel $request)
    {
        try {
            $data = $request->only(['hostel_name', 'location']);
            $this->hostel_repository->create($data);

            return Qs::jsonStoreOk('Hostel created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create hostel: ' . $th->getMessage());
        }
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
        try {
            $data = $request->only(['hostel_name', 'location']);
            $this->hostel_repository->update($id, $data);

            return Qs::jsonUpdateOk('Hostel updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update hostel: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->hostel_repository->find($id)->delete();
            return Qs::goBackWithSuccess('Hostel deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete hostel referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete hostel: ' . $th->getMessage());
        }
    }
}
