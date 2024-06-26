<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ApplicantPayment extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    
    protected  $fillable = ['applicant_id', 'amount', 'payment_method_id'];
}
