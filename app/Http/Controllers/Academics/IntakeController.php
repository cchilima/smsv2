<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Intakes\Intake;
use App\Http\Requests\Intakes\IntakeUpdate;
use App\Repositories\Academics\IntakesRepository;
use Illuminate\Database\QueryException;
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
        try {
            $data = $req->only(['name']);
            $this->intakes->create($data);

            return Qs::jsonStoreOk('Academic period intake created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create academic period intake: ' . $th->getMessage());
        }
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
        try {
            $data = $req->only(['name']);
            $this->intakes->update($id, $data);

            return Qs::jsonUpdateOk('Academic period intake updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update academic period intake: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->intakes->find($id)->delete();
            return Qs::goBackWithSuccess('Academic period intake deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete academic period intake referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete academic period intake: ' . $th->getMessage());
        }
    }
}
