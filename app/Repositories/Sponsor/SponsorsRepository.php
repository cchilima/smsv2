<?php

namespace App\Repositories\Sponsor;
use \App\Models\Sponsorship\Sponsor;

class SponsorsRepository
{
    public function create($data)
    {
        return Sponsor::create($data);
    }

    public function getAll($executeQuery = true)
    {
        $query = Sponsor::query();

        return $executeQuery ? $query->get() : $query;
    }

    public function update($id, $data)
    {
        return Sponsor::find($id)->update($data);
    }

    public function find($id)
    {
        return Sponsor::find($id);
    }
}
