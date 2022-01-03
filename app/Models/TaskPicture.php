<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @TaskPicture
 */
class TaskPicture extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'task',
        'picture'
    ];

    /**
     * @return BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task');
    }

    /**
     * @return BelongsTo
     */
    public function picture()
    {
        return $this->belongsTo(Picture::class, 'picture');
    }
}
