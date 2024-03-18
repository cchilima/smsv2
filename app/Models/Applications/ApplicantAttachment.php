<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'attachment',
        'applicant_id',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
