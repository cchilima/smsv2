<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\StudyMode\StudyMode;
use App\Http\Requests\StudyMode\StudyModeUpdate;
use App\Repositories\Academics\StudyModeRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StudyModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $studymode;
    public function __construct(StudyModeRepository $studymode)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->studymode = $studymode;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudyMode $req)
    {
        try {
            $data = $req->only(['name', 'description']);
            $this->studymode->create($data);

            return Qs::jsonStoreOk('Study mode created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create study mode: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mode['mode'] = $studymode = $this->studymode->find($id);

        return !is_null($studymode) ? view('pages.studymodes.edit', $mode)
            : Qs::goWithDanger('studymodes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudyModeUpdate $req, $id)
    {

        try {
            $data = $req->only(['name', 'description']);
            $this->studymode->update($id, $data);

            return Qs::jsonUpdateOk('Study mode updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update study mode: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->studymode->find($id)->delete();
            return Qs::goBackWithSuccess('Study mode deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete study mode referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete sctudy mode: ' . $th->getMessage());
        }
    }
}
