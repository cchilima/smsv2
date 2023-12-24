<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = ['name','type','chart_of_account_id'];
    use HasFactory;
}
