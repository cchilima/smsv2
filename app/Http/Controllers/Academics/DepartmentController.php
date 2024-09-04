<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\departments\Department;
use App\Http\Requests\departments\DepartmentUpdate;
use App\Repositories\Academics\DepartmentsRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $department;
    public function __construct(DepartmentsRepository $department)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);


        $this->department = $department;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Department $req)
    {
        try {
            if ($req->hasFile('cover')) {
                $data = $req->only(['school_id', 'name', 'description', 'cover']);
                $logo = $req->file('cover');
                $f = Qs::getFileMetaData($logo);
                $f['name'] = $data['name'] . 'logo.' . $f['ext'];
                $f['path'] = $logo->storeAs(Qs::getPublicUploadPathDep(), $f['name']);
                $logo_path = asset('storage/depart/' . $f['name']);
                $data['cover'] = $logo_path;
                $data['slug'] = $data['name'];
                $this->department->create($data);
            } else {
                $data = $req->only(['school_id', 'name', 'description']);
                $data['slug'] = $data['name'];
                $this->department->create($data);
            }

            return Qs::jsonStoreOk('Department created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create department: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['departmentId'] = $id;
        $data['department'] = $department = $this->department->find($id);

        return !is_null($department) ? view('pages.departments.show', $data)
            : Qs::goWithDanger('departments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['departments'] = $department = $this->department->find($id);
        return !is_null($department) ? view('pages.departments.edit', $data)
            : Qs::goWithDanger('departments.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdate $req, string $id)
    {
        try {
            if ($req->hasFile('cover')) {
                $data = $req->only(['name', 'description', 'cover', 'slug']);
                $logo = $req->file('cover');
                $f = Qs::getFileMetaData($logo);
                $f['name'] = $data['name'] . 'logo.' . $f['ext'];
                $f['path'] = $logo->storeAs(Qs::getPublicUploadPathDep(), $f['name']);
                $logo_path = asset('storage/depart/' . $f['name']);
                $data['cover'] = $logo_path;
                $data['slug'] = $data['name'];
                $this->department->update($id, $data);
            } else {
                $data = $req->only(['name', 'description', 'slug']);
                $data['slug'] = $data['name'];
                $this->department->update($id, $data);
            }

            return Qs::jsonUpdateOk('Department updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update department: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->department->find($id)->delete();
            return Qs::goBackWithSuccess('Department deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete department referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete department: ' . $th->getMessage());
        }
    }
}
