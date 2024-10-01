<?php

namespace App\Repositories\Profile;

use App\Models\Profile\MaritalStatus;

class MaritalStatusRepository
{
    public function create($data)
    {
        return MaritalStatus::create($data);
    }

    public function getAll($executeQuery = true)
    {
        $query = MaritalStatus::query();

        return $executeQuery ? $query->get() : $query;
    }

    public function update($id, $data)
    {
        return MaritalStatus::find($id)->update($data);
    }

    public function find($id)
    {
        return MaritalStatus::find($id);
    }
}
