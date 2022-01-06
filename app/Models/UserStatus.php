<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @UserStatus
 */
class UserStatus extends Model
{
    use HasFactory;

    /**
     * @const
     */
    const STATUS_ADMIN = 1;

    /**
     * @const
     */
    const STATUS_PM = 2;

    /**
     * @const
     */
    const STATUS_REGULAR = 3;

    /**
     * @var string[]
     */
    protected $fillable = [
        'status',
        'description'
    ];

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'status');
    }

    /**
     * @return HasMany
     */
    public function userStatusChangeRequests()
    {
        return $this->hasMany(UserStatusChangeRequest::class, 'user_requested_status');
    }
}
