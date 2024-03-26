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

    public function getAllStudentAnnouncements($order = 'title')
    {
        return Announcement::join('user_types', 'user_types.id', 'announcements.addressed_to')->where('announcements.archived', false)->where('user_types.name', 'Student')->orderBy($order)->get(['announcements.title', 'announcements.description', 'announcements.addressed_to', 'attachment', 'user_types.name', 'announcements.created_at as created_at', 'announcements.id as id', 'announcements.archived']);
    }

    public function getAllInstructorAnnouncements($order = 'title')
    {
        return Announcement::join('user_types', 'user_types.id', 'announcements.addressed_to')->where('announcements.archived', false)->where('user_types.name', 'Instructor')->orderBy($order)->get(['announcements.title', 'announcements.description', 'announcements.addressed_to', 'attachment', 'user_types.name', 'announcements.created_at as created_at', 'announcements.id as id' , 'announcements.archived']);
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
