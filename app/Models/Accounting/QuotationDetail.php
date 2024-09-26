<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;

    protected $fillable = ['fee_id', 'quotation_id', 'amount'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }
}
