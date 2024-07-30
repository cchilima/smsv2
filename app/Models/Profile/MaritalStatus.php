<?php

namespace App\Models\Profile;

use App\Models\Users\UserPersonalInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['status', 'description'];

    public function userPersonalInformation()
    {
        return $this->hasMany(UserPersonalInformation::class, 'marital_status_id');
    }
}
