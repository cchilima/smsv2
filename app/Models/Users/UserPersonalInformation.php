<?php

namespace App\Models\Users;

use App\Models\Profile\MaritalStatus;
use App\Models\Residency\Country;
use App\Models\Residency\Province;
use App\Models\Residency\Town;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UserPersonalInformation extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['date_of_birth', 'street_main', ' post_code', 'nrc', 'passport_number', 'passport_photo_path', 'user_id', 'telephone', 'mobile', 'marital_status_id', 'town_id', 'province_id', 'country_id'];


        /**
     * Update attributes using query builder and log audit.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return int
     */
    public static function updateAndAudit(array $attributes, array $values)
    {
        // Perform the update
        $affectedRows = static::where($attributes)->update($values);

        // Manually log the audit for each affected row
        foreach (static::where($attributes)->get() as $model) {
            Auditor::execute($model);
        }

        return $affectedRows;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userMaritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
