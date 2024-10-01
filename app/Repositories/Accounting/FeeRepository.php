<?php

namespace App\Repositories\Accounting;

use App\Models\Accounting\Fee;

class FeeRepository
{
    public function create($data)
    {
        return Fee::create($data);
    }

    public function getAll($executeQuery = true)
    {
        $query = Fee::query();

        return $executeQuery ? $query->get() : $query;
    }

    public function update($id, $data)
    {
        return Fee::find($id)->update($data);
    }

    public function find($id)
    {
        return Fee::find($id);
    }
}
