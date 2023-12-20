<?php

namespace App\Models\Users;

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
}
