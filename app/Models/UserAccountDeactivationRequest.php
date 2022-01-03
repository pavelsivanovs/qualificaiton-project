<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @UserAccountDeactivationRequest
 */
class UserAccountDeactivationRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user',
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
    public function requestStatus()
    {
        return $this->belongsTo(RequestStatus::class, 'request_status');
    }
}
