<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantPayment extends Model
{
    use HasFactory;

    protected  $fillable = ['applicant_id', 'amount'];
}
