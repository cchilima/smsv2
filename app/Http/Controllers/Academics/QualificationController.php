<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Qualifications\Qualification;
use App\Http\Requests\Qualifications\QualificationUpdate;
use App\Repositories\Academics\QualificationsRepository;
use Illuminate\Database\QueryException;
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
        try {
            $data = $req->only(['name']);
            $data['slug'] = $data['name'];
            $this->qualifications->create($data);

            return Qs::jsonStoreOk('Qualification created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create qualification: ' . $th->getMessage());
        }
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
        try {
            $data = $req->only(['name']);
            $data['slug'] = $data['name'];
            $this->qualifications->update($id, $data);

            return Qs::jsonUpdateOk('Qualification updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update qualification: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->qualifications->find($id)->delete();
            return Qs::goBackWithSuccess('Qualification deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete qualification referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete qualification: ' . $th->getMessage());
        }
    }
}
