<?php

namespace App\Models\Academics;

use App\Models\Accounting\Fee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodFee extends Model
{
    protected $fillable = ['amount','academic_period_id','fee_id','status'];
    use HasFactory;

    public function fee(){
        return $this->belongsTo(Fee::class,'fee_id','id');
    }
    public function academic_period(){
        return $this->belongsTo(AcademicPeriod::class,'academic_period_id','id');
    }
}
