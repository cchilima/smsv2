<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Program;

class ProgramsRepository
{
    public function create($data)
    {
        return Program::create($data);
    }

    public function getAll($order = 'name')
    {
        return Program::with('department','qualification')->orderBy($order)->get();
    }
    public function update($id, $data)
    {
        return Program::find($id)->update($data);
    }
    public function find($id)
    {
        return Program::find($id)->with('department','qualification','courses')->get();
    }
    public function findOne($id)
    {
        return Program::find($id);
    }
    public function findOneP($id)
    {
        return Program::find($id)->with('levels.courses')->firstOrFail();
    }

}
