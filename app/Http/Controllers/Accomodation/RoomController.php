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
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $hostel_repository, $room_repository;
    public function __construct(HostelRepository $hostel_repository, RoomRepository $room_repository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->hostel_repository = $hostel_repository;
        $this->room_repository = $room_repository;

    }
    public function index()
    {
        $data['rooms'] = $this->room_repository->getAll();
        $data['hostels'] = $this->hostel_repository->getAll();
        return view('pages.rooms.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Room $request)
    {
        $data = $request->only(['hostel_id', 'room_number','capacity','gender']);

        $hostel = $this->room_repository->create($data);

        if ($hostel) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::jsonError(__('msg.create_failed'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = $this->room_repository->find($id);
        $data['hostels'] = $this->hostel_repository->getAll();

        return !is_null($room) ? view('pages.rooms.edit', $data,compact('room'))
            : Qs::goWithDanger('pages.rooms.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdate $request, string $id)
    {
        $data = $request->only(['hostel_id', 'room_number','capacity','gender']);
        $this->room_repository->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->room_repository->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
