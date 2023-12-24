<?php

namespace App\Models\Profile;

use App\Models\Users\UserPersonalInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'description'];

    public function userPersonalInformation()
    {
        return $this->hasMany(UserPersonalInformation::class, 'marital_status_id');
    }
}
