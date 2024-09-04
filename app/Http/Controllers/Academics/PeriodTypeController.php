<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\PeriodTypes\PeriodType;
use App\Http\Requests\PeriodTypes\PeriodTypeUpdate;
use App\Repositories\Academics\PeriodTypeRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PeriodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $periodTypes;
    public function __construct(PeriodTypeRepository $periodTypes)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);


        $this->periodTypes = $periodTypes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeriodType $req)
    {
        try {
            $data = $req->only(['name', 'description']);

            $this->periodTypes->create($data);

            return Qs::jsonStoreOk('Academic period type created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create academic period type: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type['type'] = $periodtypes = $this->periodTypes->find($id);

        return !is_null($periodtypes) ? view('pages.academicperiodtypes.edit', $type)
            : Qs::goWithDanger('pages.academicperiodtypes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodTypeUpdate $req, string $id)
    {
        try {
            $data = $req->only(['name', 'description']);
            $this->periodTypes->update($id, $data);

            return Qs::jsonUpdateOk('Academic period type updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update academic period type: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->periodTypes->find($id)->delete();
            return Qs::goBackWithSuccess('Academic period type deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] === 1451) {
                return Qs::goBackWithError('Cannot delete an academic period type referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete academic period type: ' . $th->getMessage());
        }
    }
}
