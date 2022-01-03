<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @RequestStatus
 */
class RequestStatus extends Model
{
    use HasFactory;

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
    public function userAccountDeactivationRequests()
    {
        return $this->hasMany(UserAccountDeactivationRequest::class, 'request_status');
    }

    /**
     * @return HasMany
     */
    public function userStatusChangeRequests()
    {
        return $this->hasMany(UserStatusChangeRequest::class, 'request_status');
    }
}
