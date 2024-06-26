<?php

namespace App\Repositories\Accommodation;

use App\Models\Accomodation\Hostel;

class HostelRepository
{
    public function create($data)
    {
        return Hostel::create($data);
    }

    public function getAll()
    {
        return Hostel::orderBy('hostel_name')->get();
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
