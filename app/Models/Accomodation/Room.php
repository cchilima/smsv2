<?php

namespace App\Models\Accomodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Room extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['hostel_id', 'room_number','capacity','gender'];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'id');
    }

    public function bedSpaces()
    {
        return $this->hasMany(BedSpace::class, 'room_id', 'id');
    }
}
