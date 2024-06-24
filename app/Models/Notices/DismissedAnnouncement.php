<?php

namespace App\Models\Notices;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DismissedAnnouncement extends Model implements AuditableContract
{
    use HasFactory, Auditable;

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
