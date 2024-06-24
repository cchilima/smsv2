<?php

namespace App\Models\Residency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'alpha_2_code',
        'alpha_3_code',
        'country',
        'nationality',
        'dialing_code'
    ];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }
}
