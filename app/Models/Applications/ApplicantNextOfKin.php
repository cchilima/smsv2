<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\Residency\{Town, Province, Country, Relationship};

class ApplicantNextOfKin extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['full_name', 'telephone', 'mobile', 'relationship_id', 'address', 'town_id', 'province_id', 'country_id'];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    public function relationship()
    {
        return $this->belongsTo(Relationship::class,'relationship_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function town()
    {
        return $this->belongsTo(Town::class,'town_id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class,'province_id');
    }
}
