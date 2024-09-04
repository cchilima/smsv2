<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\MaritalStatuses\MaritalStatus;
use App\Http\Requests\MaritalStatuses\MaritalStatusUpdate;
use App\Repositories\Profile\MaritalStatusRepository;
use Illuminate\Database\QueryException;
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
        try {
            $data = $request->only(['status', 'description']);
            $this->maritalStatuses->create($data);

            return Qs::jsonStoreOk('Marital status created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create marital status: ' . $th->getMessage());
        }
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
        try {
            $data = $request->only(['status', 'description']);
            $this->maritalStatuses->update($id, $data);

            return Qs::jsonUpdateOk('Marital status updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update marital status: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->maritalStatuses->find($id)->delete();
            return Qs::goBackWithSuccess('Marital status deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete a marital status referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError(
                'Failed to delete marital status: ' . $th->getMessage()
            );
        }
    }
}
