<?php

namespace App\Models\Accomodation;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Booking extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['student_id','bed_space_id','booking_date','expiration_date'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function bedSpace()
    {
        return $this->belongsTo(BedSpace::class, 'bed_space_id', 'id');
    }
}
