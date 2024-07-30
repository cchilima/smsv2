<?php

namespace App\Http\Controllers\Notices;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Middleware\Custom\TeamSAT;
use App\Http\Requests\Announcements\Announcement;
use App\Http\Requests\Announcements\AnnouncementUpdate;
use App\Repositories\Announcements\AnnouncementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{

    protected $announcement;

    public function __construct(AnnouncementRepository $announcement)
    {
        // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);
        $this->middleware(TeamSAT::class, ['except' => ['destroy',]]);

        $this->announcement = $announcement;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements['userTypes'] = $this->announcement->getUserTypes();
        $announcements['announcements'] = $this->announcement->getAll();

        return view('pages.announcements.index', $announcements);
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
    public function store(Request $request)
    {
        // Set 'addressed_to' to null if 'everyone' is selected
        $request['addressed_to'] = $request['addressed_to'] === 'everyone' ? null : $request['addressed_to'];

        if ($request->hasFile('attachment')) {
            $data = $request->only(['addressed_to', 'title', 'description', 'attachment', 'archived']);
            $attachment = $request->file('attachment');

            $f = Qs::getFileMetaData($attachment);

            $f['name'] = $data['attachment'] . 'announcement.' . $f['ext'];
            $f['path'] = $attachment->storeAs(Qs::getPublicUploadPathAnnouncements(), $f['name']);
            $attachment_path = asset('storage/announcements/' . $f['name']);
            $data['attachment'] = $attachment_path;
            $this->announcement->create($data);
        } else {
            $data = $request->only(['addressed_to', 'title', 'description', 'archived']);
            $this->announcement->create($data);
        }

        return Qs::jsonStoreOk();
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

        $data['announcement'] = $announcement = $this->announcement->find($id);

        $data['userTypes'] = $this->announcement->getUserTypes();


        return !is_null($announcement) ? view('pages.announcements.edit', $data)
            : Qs::goWithDanger('pages.announcements.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->hasFile('attachment')) {
            $data = $request->only(['addressed_to', 'title', 'description', 'attachment', 'archived']);
            $attachment = $request->file('attachment');

            $f = Qs::getFileMetaData($attachment);

            $f['name'] = $data['attachment'] . 'announcement.' . $f['ext'];
            $f['path'] = $attachment->storeAs(Qs::getPublicUploadPathAnnouncements(), $f['name']);
            $attachment_path = asset('storage/announcements/' . $f['name']);
            $data['attachment'] = $attachment_path;
            $this->announcement->update($id, $data);
        } else {

            $data = $request->only(['addressed_to', 'title', 'description', 'archived']);
            $this->announcement->update($id, $data);
        }

        return Qs::jsonStoreOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->announcement->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }


    public function ShowAnnouncement($id)
    {
        $data['announcement'] = $this->announcement->find($id);

        return view('pages.announcements.show_announcement', $data);
    }

    public function dismissAnnouncement(string $announcement_id)
    {
        try {
            $userId = Auth::user()->id;
            $this->announcement->dismissAnnouncement($userId, $announcement_id);
            return redirect()->back()->with('flash_success', __('Announcement dismissed'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('flash_error', __('Failed to dismiss announcement'));
            //throw $th;
        }
    }
}
