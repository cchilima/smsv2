<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class StudyMode extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'description'];
}
