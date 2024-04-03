<?php

namespace App\Repositories\Announcements;


use App\Models\Users\UserType;
use App\Models\Notices\Announcement;

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
        return Announcement::with('userType')
            ->latest('created_at')
            ->where('archived', false)
            ->where('addressed_to', null)
            ->orWhereHas('userType', fn ($query) => $query->where('user_types.name', $userType))
            ->get();
    }

    public function dismissAnnouncement(string $user_id, string $announcement_id)
    {
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
