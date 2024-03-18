<?php

namespace App\Repositories\Residency;

use App\Models\Residency\{Town};

class TownRepository
{

    public function getAll()
    {
        return Town::all();
    }

    public function create($data)
    {
        return Town::create($data);
    }

    public function update($town, $data)
    {
        return $town->update($data);
    }

    public function delete($town)
    {
        return $town->delete();
    }
}
