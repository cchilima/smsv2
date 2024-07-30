<?php

namespace App\Models\Accounting;

use App\Models\Admissions\Student;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Receipt extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['student_id', 'invoice_id', 'amount', 'collected_by', 'payment_method_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class,'collected_by');
    }
}
