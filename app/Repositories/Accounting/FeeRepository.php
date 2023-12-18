<?php

namespace App\Repositories\Accounting;

use App\Models\Accounting\Fee;

class FeeRepository
{
    public function create($data)
    {
        return Fee::create($data);
    }

    public function getAll($order = 'name')
    {
        return Fee::orderBy($order,'asc')->get();
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
