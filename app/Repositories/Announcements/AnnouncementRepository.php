<?php

namespace App\Repositories\Announcements;


use App\Models\Users\UserType;
use App\Models\Notices\Announcement;
use App\Models\Notices\DismissedAnnouncement;
use Illuminate\Support\Facades\Auth;

class AnnouncementRepository
{
    public function create($data)
    {
        return Announcement::create($data);
    }

    public function getAll($order = 'title')
    {
        return Announcement::orderBy($order)->get();
    }

    public function getAllByUserType($userType)
    {
        $userId = Auth::user()->id;

        // All announcements that are addressed to and have not been dismissed by the user
        $announcements = Announcement::with(['userType', 'dismissedAnnouncements'])
            ->latest('created_at')
            ->where('archived', false)
            ->where(function ($query) use ($userType) {
                $query->whereHas('userType', function ($query) use ($userType) {
                    $query->where('user_types.name', $userType);
                })->orWhere('addressed_to', null);
            })
            ->whereDoesntHave('dismissedAnnouncements', function ($query) use ($userId) {
                $query->where('dismissed_announcements.user_id', $userId);
            })
            ->get();

        return $announcements;
    }

    public function dismissAnnouncement(string $user_id, string $announcement_id)
    {
        // Create a new dismissed announcement record
        return DismissedAnnouncement::create([
            'user_id' => $user_id,
            'announcement_id' => $announcement_id
        ]);
    }

    public function update($id, $data)
    {
        return Announcement::find($id)->update($data);
    }

    public function find($id)
    {
        // return Announcement::with('userTypes')->find($id);
        return Announcement::find($id);
    }


    // get user types
    public function getUserTypes()
    {
        return UserType::all();
    }
}
