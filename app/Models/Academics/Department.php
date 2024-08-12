<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'school_id', 'description', 'slug', 'cover'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
