<?php

namespace App\Models;

use App\Enums\UserRoleTypeCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property bool $isAdmin
 * @method static Builder isAdmin() @param bool $isAdmin = true
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * username
     * name
     * backend url
     * role
     * integrations
     *
     *
     *
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'username',
    //     'backend_url',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    function scopeIsAdmin($query, $isAdmin = true)
    {
        return $query->where('role_code',  $isAdmin ? UserRoleTypeCode::ADMIN->value : UserRoleTypeCode::CLIENT->value);
    }

    function getIsAdminAttribute(): bool
    {
        return $this->role_code == UserRoleTypeCode::ADMIN->value;
    }
}
