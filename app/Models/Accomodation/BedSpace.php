<?php

namespace App\Models\Accomodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BedSpace extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['room_id','bed_number','is_available'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'bed_space_id', 'id');
    }
}


