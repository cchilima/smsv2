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
use Illuminate\Database\QueryException;
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
        try {
            $data = $request->only(['room_id', 'bed_number', 'is_available']);
            $room = $this->room_repository->find($data['room_id']);
            $existing_bed_spaces = $this->bed_space_repository->capacity($data['room_id']);

            if ($existing_bed_spaces  >= $room->capacity) {
                throw new \Exception('Maximum room capacity reached (' . $room->capacity . ')');
            }

            $this->bed_space_repository->create($data);

            return Qs::jsonStoreOk('Bed space created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create bed space: ' . $th->getMessage());
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
        try {
            $data = $request->only(['room_id', 'bed_number', 'is_available']);
            $this->bed_space_repository->update($id, $data);

            return Qs::jsonUpdateOk('Bed space updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update bed space: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->bed_space_repository->find($id)->delete();
            return Qs::goBackWithSuccess('Bed space deleted successfully');;
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete bed space referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete bed space: ' . $th->getMessage());
        }
    }
}
