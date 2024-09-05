<?php

namespace App\Http\Controllers\Accomodation;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Accomodation\Room;
use App\Http\Requests\Accomodation\RoomUpdate;
use App\Repositories\Accommodation\HostelRepository;
use App\Repositories\Accommodation\RoomRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $hostel_repository, $room_repository;
    public function __construct(HostelRepository $hostel_repository, RoomRepository $room_repository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->hostel_repository = $hostel_repository;
        $this->room_repository = $room_repository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Room $request)
    {
        try {
            $data = $request->only(['hostel_id', 'room_number', 'capacity', 'gender']);
            $this->room_repository->create($data);

            return Qs::jsonStoreOk('Room created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create room: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = $this->room_repository->find($id);
        $data['hostels'] = $this->hostel_repository->getAll();

        return !is_null($room) ? view('pages.rooms.edit', $data, compact('room'))
            : Qs::goWithDanger('pages.rooms.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdate $request, string $id)
    {
        try {
            $data = $request->only(['hostel_id', 'room_number', 'capacity', 'gender']);
            $this->room_repository->update($id, $data);

            return Qs::jsonUpdateOk('Room updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update room: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->room_repository->find($id)->delete();
            return Qs::goBackWithSuccess('Room deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete room referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete room: ' . $th->getMessage());
        }
    }
}
