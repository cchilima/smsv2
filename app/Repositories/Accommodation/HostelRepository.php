<?php

namespace App\Repositories\Accommodation;

use App\Models\Accomodation\Hostel;

class HostelRepository
{
    public function create($data)
    {
        return Hostel::create($data);
    }

    public function getAll($orderBy = 'hostel_name', $executeQuery = true)
    {
        $query = Hostel::orderBy($orderBy, 'asc');

        return $executeQuery ? $query->get() : $query;
    }

    public function update($id, $data)
    {
        return Hostel::find($id)->update($data);
    }

    public function find($id)
    {
        return Hostel::find($id);
    }
}
