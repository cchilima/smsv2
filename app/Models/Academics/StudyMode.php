<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMode extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];
}
