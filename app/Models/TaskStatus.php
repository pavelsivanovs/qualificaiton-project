<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @TaskStatus
 */
class TaskStatus extends Model
{
    use HasFactory;

    /**
     * @const
     */
    const STATUS_IN_PROCESS = 1;

    /**
     * @const
     */
    const STATUS_PENDING = 2;

    /**
     * @const
     */
    const STATUS_TESTING = 3;

    /**
     * @const
     */
    const STATUS_COMPLETED = 4;

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
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status');
    }
}
