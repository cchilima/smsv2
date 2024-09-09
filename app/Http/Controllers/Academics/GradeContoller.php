<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\GradeRepository;
use Illuminate\Http\Request;

class GradeContoller extends Controller
{
    protected $gradeRepo;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy']]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);

        $this->gradeRepo = $gradeRepository;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            if ($request->ajax()) {
                if ($request->value > 100 || $request->value < 0) {
                    throw new \Exception('Total must be a number from 0 to 100.');
                }

                // Get grade
                $grade = $this->gradeRepo->find($request->pk);

                // Update grade
                $grade->update(['total' => $request->value]);

                return Qs::jsonUpdateOk('Marks updated successfully');
            }
        } catch (\Exception $e) {
            return Qs::jsonError('Failed to update marks: ' . $e->getMessage());
        }
    }
}
