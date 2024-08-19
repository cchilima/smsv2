<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\user;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'reason', 'invoice_detail_id', 'amount', 'status', 'issued_by', 'authorizers'];


    public function invoiceDetail()
    {
        return $this->belongsTo(invoiceDetail::class, 'invoice_detail_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

}
