<?php

namespace App\Models\Users;

use App\Models\Profile\MaritalStatus;
use App\Models\Residency\Country;
use App\Models\Residency\Province;
use App\Models\Residency\Town;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPersonalInformation extends Model
{
    use HasFactory;

    protected $fillable = [ 'date_of_birth', 'street_main', ' post_code', 'nrc', 'passport' ,'user_id', 'telephone', 'mobile', 'marital_status_id', 'town_id', 'province_id', 'country_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userMaritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class,'marital_status_id');
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


