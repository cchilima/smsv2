<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admissions\Student;

use App\Models\A\User;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'raised_by', 'cancelled', 'academic_period_id'];

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function statements()
    {
        return $this->hasMany(Statement::class, 'invoice_id')->where('amount', '>' , 0);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
