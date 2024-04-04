<?php

namespace App\Models\Notices;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DismissedAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'announcement_id'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }
}
