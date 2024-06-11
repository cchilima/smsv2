<?php

namespace App\Models\Notices;

use App\Models\Users\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'attachment', 'addressed_to', 'archived'];

    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'addressed_to', 'id');
    }

    public function dismissedAnnouncements(): HasMany
    {
        return $this->hasMany(DismissedAnnouncement::class);
    }
}
