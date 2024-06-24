<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PaymentMethod extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name'];
}
