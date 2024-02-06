<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'school_id', 'description', 'slug', 'cover'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
