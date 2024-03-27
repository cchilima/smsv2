<?php

namespace App\Models\Notices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'attachment', 'addressed_to', 'archived'];
}
