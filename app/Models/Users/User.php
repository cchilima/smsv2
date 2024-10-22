<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 */
class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable, Auditable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'gender',
        'user_type_id',
        'force_password_reset'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userType(){

        return $this->belongsTo(UserType::class);
    }

    public function hasPermissionTo($permission)
    {
        // Check if the user has the permission directly
        if ($this->getDirectPermissions()->contains('name', $permission)) {
            return true;
        }

        // Check if the user's user_type has the permission
        if ($this->userType && $this->userType->permissions->contains('name', $permission)) {
            return true;
        }

        return false;
    }

    /**
     * Define a one-to-one relationship with UserPersonalInfo.
     */
    public function userPersonalInfo()
    {
        return $this->hasOne(UserPersonalInformation::class);
    }

    /**
     * Define a one-to-one relationship with UserNextOfKin.
     */
    public function userNextOfKin()
    {
        return $this->hasOne(UserNextOfKin::class);
    }

    /**
     * Define a one-to-one relationship with Student.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
