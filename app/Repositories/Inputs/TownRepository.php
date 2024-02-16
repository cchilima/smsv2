<?php

namespace App\Repositories\Inputs;


use App\Models\Residency\{Town};

class TownRepository
{

    public function getAll()
    {
        return Town::all(['id', 'name']);
    }
}
