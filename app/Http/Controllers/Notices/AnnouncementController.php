<?php

namespace App\Http\Controllers\Notices;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Announcements\Announcement;
use App\Http\Requests\Announcements\AnnouncementUpdate;
use App\Repositories\Announcements\AnnouncementRepository;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    protected $announcement;

    public function __construct(AnnouncementRepository $announcement)
    {
       // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
       // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

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
        if($request->hasFile('attachment')) {
            $data = $request->only(['user_type_id','title', 'description','attachment', 'addressed_to', 'archived']);
            $attachment = $request->file('attachment');

            $f = Qs::getFileMetaData($attachment);

            $f['name'] = $data['attachment'].'announcement.' . $f['ext'];
            $f['path'] = $attachment->storeAs(Qs::getPublicUploadPathAnnouncements(), $f['name']);
            $attachment_path = asset('storage/announcements/' . $f['name']);
            $data['attachment'] = $attachment_path;
            $this->announcement->create($data);

        }else{

            $data = $request->only(['user_type_id','title', 'description', 'addressed_to', 'archived']);
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
        

        return !is_null($announcement  ) ? view('pages.announcements.edit',$data)
            : Qs::goWithDanger('pages.announcements.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->hasFile('attachment')) {
            $data = $request->only(['user_type_id','title', 'description','attachment', 'addressed_to', 'archived']);
            $attachment = $request->file('attachment');

            $f = Qs::getFileMetaData($attachment);

            $f['name'] = $data['attachment'].'announcement.' . $f['ext'];
            $f['path'] = $attachment->storeAs(Qs::getPublicUploadPathAnnouncements(), $f['name']);
            $attachment_path = asset('storage/announcements/' . $f['name']);
            $data['attachment'] = $attachment_path;
            $this->announcement->update($id, $data);

        }else{

            $data = $request->only(['user_type_id','title', 'description', 'addressed_to', 'archived']);
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

    /**
     * Remove the specified resource from storage.
     */
    public function ShowAnnouncement($id)
    {
        $data['announcement'] = $this->announcement->find($id);

        return view('pages.announcements.show_announcement', $data);
    }
}
