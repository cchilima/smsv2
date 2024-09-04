<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Schools\School;
use App\Http\Requests\Schools\SchoolUpdate;
use App\Repositories\Academics\SchooolRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $schoolRepo;
    public function __construct(SchooolRepository $schoolRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->schoolRepo = $schoolRepo;
    }

    /**
     * Generate a custom slug for a given school name
     * 
     * @param string $schoolName The name of the school
     * @return string
     */
    private function generateSchoolSlug(string $schoolName): string
    {
        $lowerCaseName = Str::lower($schoolName);
        return Str::slug(Str::after($lowerCaseName, 'school of '));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(School $req)
    {
        try {
            $data = $req->only(['name', 'description']);

            // Generate slug
            $data['slug'] = $this->generateSchoolSlug($data['name']);

            $this->schoolRepo->create($data);

            return Qs::jsonStoreOk('School created successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] === 1062) {
                return Qs::jsonError('A school with the generated slug ' . '"' . $data['slug'] . '" already exists');
            }
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create school: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $school['school'] = $school = $this->schoolRepo->find($id);

        return !is_null($school) ? view('pages.schools.edit', $school)
            : Qs::goWithDanger('schools.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolUpdate $req, $id)
    {
        try {
            $data = $req->only(['name', 'description']);

            $data['slug'] = $this->generateSchoolSlug($data['name']);

            $this->schoolRepo->update($id, $data);

            return Qs::jsonUpdateOk('School updated successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] === 1062) {
                return Qs::jsonError('A school with the generated slug ' . '"' . $data['slug'] . '" already exists');
            }
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update school: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->schoolRepo->find($id)->delete();
            return Qs::goBackWithSuccess('school deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] === 1451) {
                return Qs::goBackWithError('Cannot delete a school referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete school: ' . $th->getMessage());
        }
    }
}
