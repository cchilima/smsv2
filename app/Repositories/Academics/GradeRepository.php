<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Grade;

class GradeRepository
{
    public function getAll()
    {
        return Grade::all();
    }

    public function find($grade_id)
    {
        return Grade::find($grade_id);
    }
}
