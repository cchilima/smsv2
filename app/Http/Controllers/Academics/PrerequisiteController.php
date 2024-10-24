<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Prerequisites\Prerequisite;
use App\Http\Requests\Prerequisites\PrerequisiteUpdate;
use App\Models\Academics\Course;
use App\Repositories\Academics\CourseRepository;
use App\Repositories\Academics\PrerequisiteRepository;
use Illuminate\Http\Request;

class PrerequisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $prerequisiteRepo, $coursesRepo;
    public function __construct(PrerequisiteRepository $prerequisiteRepo, CourseRepository $coursesRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->prerequisiteRepo = $prerequisiteRepo;
        $this->coursesRepo = $coursesRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Prerequisite $req)
    {
        try {
            $data = $req->only(['course_id', 'prerequisite_course_id']);

            foreach ($data['prerequisite_course_id'] as $courseID) {
                $this->prerequisiteRepo->create([
                    'prerequisite_course_id' => $courseID,
                    'course_id' => $data['course_id'],
                ]);
            }

            return Qs::jsonStoreOk('Prerequisites added successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to add prerequisites: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = Qs::decodeHash($id);
        $courses['courses'] = $this->prerequisiteRepo->find($id);
        $courses['pcourses'] = $this->coursesRepo->getAll();
        $courses['course'] = $this->prerequisiteRepo->findOne($id);
        //dd($courses);
        return view('pages.prerequisites.edit', $courses);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrerequisiteUpdate $req, string $id)
    {
        try {
            $id = Qs::decodeHash($id);
            $data = $req->only(['course_id', 'prerequisite_course_id']);

            foreach ($data['prerequisite_course_id'] as $courseID) {
                $this->prerequisiteRepo->updateOrInsert($id, $courseID);
            }
            return Qs::jsonUpdateOk('Prerequisites updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update prerequisites: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Qs::decodeHash($id);

            $this->prerequisiteRepo->findone($id)->delete();

            return Qs::goBackWithSuccess('Prerequisites deleted successfully');;
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete prerequisites: ' . $th->getMessage());
        }
    }
}
