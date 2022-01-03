<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @UserStatusChangeRequest
 */
class UserStatusChangeRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user',
        'user_requested_status',
        'request_status'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }

    /**
     * @return BelongsTo
     */
    public function userRequestedStatus()
    {
        return $this->belongsTo(UserStatus::class, 'user_requested_status');
    }

    /**
     * @return BelongsTo
     */
    public function requestStatus()
    {
        return $this->belongsTo(RequestStatus::class, 'request_status');
    }
}
