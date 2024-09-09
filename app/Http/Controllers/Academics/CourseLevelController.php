<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\CourseLevels\CourseLevels;
use App\Http\Requests\CourseLevels\CourseLevelsUpdate;
use App\Http\Requests\Courses\CoursesUpdate;
use App\Repositories\Academics\CourseLevelsRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $courselevels;
    public function __construct(CourseLevelsRepository $courselevels)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->courselevels = $courselevels;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseLevels $req)
    {
        try {
            $data = $req->only(['name']);
            $this->courselevels->create($data);

            return Qs::jsonStoreOk('Course level created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create course level: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['levels'] = $courselevels = $this->courselevels->find($id);

        return !is_null($courselevels) ? view('pages.levels.edit', $data)
            : Qs::goWithDanger('pages.levels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseLevelsUpdate $req, string $id)
    {
        try {
            $data = $req->only(['name']);
            $this->courselevels->update($id, $data);

            return Qs::jsonUpdateOk('Course level updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update course level: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->courselevels->find($id)->delete();
            return Qs::goBackWithSuccess('Course level deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete a course level referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete course level: ' . $th->getMessage());
        }
    }
}
