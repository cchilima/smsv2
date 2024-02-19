<?php

namespace App\Models\Academics;

use App\Models\Accounting\Fee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodFee extends Model
{
    use HasFactory;
    
    protected $fillable = ['amount','academic_period_id','fee_id','status'];

    public function fee(){
        return $this->belongsTo(Fee::class,'fee_id','id');
    }

    public function academic_period(){
        return $this->belongsTo(AcademicPeriod::class,'academic_period_id','id');
    }

    public function programs(){
        return $this->belongsToMany(Program::class, 'program_academic_period_fee', 'academic_period_fee_id', 'program_id');
    }
}
