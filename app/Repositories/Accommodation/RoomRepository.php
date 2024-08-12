<?php

namespace App\Repositories\Accommodation;

use App\Models\Accomodation\Room;

class RoomRepository
{
    public function create($data)
    {
        return Room::create($data);
    }

    public function getAll($executeQuery = true)
    {
        $query = Room::with('hostel');

        return $executeQuery ? $query->get() : $query;
    }
    public function getSpecificRooms($id)
    {
        return Room::where('hostel_id', $id)->get();
    }

    public function update($id, $data)
    {
        return Room::find($id)->update($data);
    }

    public function find($id)
    {
        return Room::with('hostel')->find($id);
    }
}
