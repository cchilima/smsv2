<?php

namespace App\Http\Controllers\Accomodation;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Accomodation\BedSpace;
use App\Http\Requests\Accomodation\BedSpaceUpdate;
use App\Repositories\Accommodation\BedSpaceRepository;
use App\Repositories\Accommodation\RoomRepository;
use Illuminate\Http\Request;

class BedSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $bed_space_repository, $room_repository;
    public function __construct(BedSpaceRepository $bed_space_repository, RoomRepository $room_repository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->bed_space_repository = $bed_space_repository;
        $this->room_repository = $room_repository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BedSpace $request)
    {
        $data = $request->only(['room_id', 'bed_number', 'is_available']);

        $currentCapacity = $this->room_repository->find($data['room_id']);
        $bed_space = $this->bed_space_repository->capacity($data['room_id']);

        if ($currentCapacity->capacity >= $bed_space) {
            $hostel = $this->bed_space_repository->create($data);
            return Qs::jsonStoreOk();
        } else {
            return Qs::json('capacity is ' . $currentCapacity->capacity . ' and is less than ' . $bed_space, false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bed_space = $this->bed_space_repository->find($id);
        $data['rooms'] = $this->room_repository->getAll();
        return !is_null($bed_space) ? view('pages.bedspace.edit', $data, compact('bed_space'))
            : Qs::goWithDanger('pages.bedspace.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BedSpaceUpdate $request, string $id)
    {
        $data = $request->only(['room_id', 'bed_number', 'is_available']);
        $this->bed_space_repository->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->bed_space_repository->find($id)->delete();
        return Qs::goBackWithSuccess('Record deleted successfully');;
    }
}
