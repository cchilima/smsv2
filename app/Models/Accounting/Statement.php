<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admissions\Student;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Statement extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['invoice_id', 'collected_from', 'collected_by', 'amount', 'payment_method_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
