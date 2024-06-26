<?php

namespace App\Models\Accomodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Hostel extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['hostel_name', 'location'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hostel_id', 'id');
    }
}
