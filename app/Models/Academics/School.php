<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'slug', 'description'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
