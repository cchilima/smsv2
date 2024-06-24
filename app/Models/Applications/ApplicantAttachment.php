<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ApplicantAttachment extends Model implements AuditableContract
{
    use HasFactory, Auditable;

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
