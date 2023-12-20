<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNextOfKin extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'telephone', 'mobile', 'relationship_id', 'town_id', 'province_id', 'country_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
