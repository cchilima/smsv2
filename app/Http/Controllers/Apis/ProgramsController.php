<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Repositories\Academics\ProgramsRepository;
use App\Repositories\Academics\QualificationsRepository;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    protected $programs, $qualifications;
    public function __construct(
        ProgramsRepository $programs,
        QualificationsRepository $qualifications
    ) {
        //$this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->programs = $programs;
        $this->qualifications = $qualifications;
    }

    public function getAll($id)
    {
        try {
            return response()->json($this->programs->getAllWithCourse($id));
        } catch (\Throwable $th) {
            return response()->json('Failed to get programs: ' . $th->getMessage(), 400);
        }
    }

    public function qualifications()
    {
        try {
            return $this->qualifications->getAll();
        } catch (\Throwable $th) {
            return response()->json('Failed to get qualifications: ' . $th->getMessage(), 400);
        }
    }
}
