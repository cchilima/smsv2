<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Sponsor\Sponsor;
use App\Http\Requests\Sponsor\UpdateSponsor;
use App\Http\Requests\Sponsor\UpdateStudentSponsor;
use App\Models\Admissions\Student;
use App\Repositories\Accommodation\HostelRepository;
use App\Repositories\Sponsor\SponsorsRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SponsorController extends Controller
{

    protected $sponsorsRepo;
    public function __construct( SponsorsRepository $sponsorsRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->sponsorsRepo = $sponsorsRepository;
    }
    /**
     * Display a listing of the resource.
     */

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
    public function store(Sponsor $request)
    {
        try {
            $data = $request->only(['name', 'description','phone','email']);
            $this->sponsorsRepo->create($data);

            return Qs::jsonStoreOk('Sponsor created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create Sponsor: ' . $th->getMessage());
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
        $sponsor = $this->sponsorsRepo->find($id);

        return !is_null($sponsor) ? view('pages.sponsors.edit', compact('sponsor'))
            : Qs::goWithDanger('pages.maritalStatuses.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSponsor $request, string $id)
    {
        try {
            $data = $request->only(['name', 'description','phone','email']);
            $this->sponsorsRepo->update($id, $data);

            return Qs::jsonUpdateOk('Sponsor updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update sponsor: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->sponsorsRepo->find($id)->delete();
            return Qs::goBackWithSuccess('Sponsor deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete a Sponsor referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError(
                'Failed to delete Sponsor: ' . $th->getMessage()
            );
        }
    }

    //sponsor management
    public function attachSponsor(UpdateStudentSponsor $request, $id)
    {
        try {
            $student = Student::find($id);
            $data = $request->only(['sponsor_id', 'level']);
            if ($data['level'] > 0){
                $student->sponsors()->attach($data['sponsor_id'],['level'=>$data['level']]);
                return Qs::jsonStoreOk('Sponsor attached successfully!');
            }else{
                $student = Student::find($id);
                $firstSponsor = $student->sponsors()->first();
                if ($firstSponsor) {
                    $student->sponsors()->detach($data['sponsor_id'],['level'=>$data['level']]);
                    return Qs::jsonStoreOk('Sponsor detached successfully!');
                } else {
                    return Qs::jsonError('No sponsors found for this student to detach ');
                }
            }

        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to attach Sponsor: ' . $th->getMessage());
        }


        //return response()->json(['message' => 'Sponsor attached successfully!']);
    }
    public function attachSponsorE(UpdateStudentSponsor $request, $id)
    {
        try {
            $student = Student::find($id);
            $data = $request->only(['sponsor_id', 'level']);
            if ($data['level'] > 0){
                $student->sponsors()->attach($data['sponsor_id'],['level'=>$data['level']]);
                return Qs::jsonStoreOk('Sponsor attached successfully!');
            }else{
                $student = Student::find($id);
                $firstSponsor = $student->sponsors()->first();
                if ($firstSponsor) {
                    $student->sponsors()->detach($data['sponsor_id'],['level'=>$data['level']]);
                    return Qs::jsonStoreOk('Sponsor detached successfully!');
                } else {
                    return Qs::jsonError('No sponsors found for this student to detach ');
                }
            }

        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to attach Sponsor: ' . $th->getMessage());
        }


        //return response()->json(['message' => 'Sponsor attached successfully!']);
    }
}
