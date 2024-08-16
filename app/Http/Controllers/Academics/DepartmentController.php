<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\departments\Department;
use App\Http\Requests\departments\DepartmentUpdate;
use App\Repositories\Academics\DepartmentsRepository;
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
    public function index()
    {
        $data['schools'] = $this->department->getSchools();
        return view('pages.departments.index', $data);
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
    public function store(Department $req)
    {

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

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['departmentId'] = $id;
        $data['department'] = $department = $this->department->find($id);

        return !is_null($department) ? view('pages.departments.show', $data)
            : Qs::goWithDanger('pages.departments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['departments'] = $department = $this->department->find($id);
        return !is_null($department) ? view('pages.academics.departments.edit', $data)
            : Qs::goWithDanger('pages.academics.departments.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdate $req, string $id)
    {
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

        return Qs::jsonStoreOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->department->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
