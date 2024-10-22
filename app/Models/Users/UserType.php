<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Spatie\Permission\Models\Permission;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UserType extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    // Define many-to-many relationship with Permission
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user_type');
    }
}
