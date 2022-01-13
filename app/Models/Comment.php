<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @Comment
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'author',
        'task',
        'comment'
    ];

    /**
     * @return BelongsTo
     */
    public function commentAuthor()
    {
        return $this->belongsTo(User::class, 'author');
    }

    /**
     * @return BelongsTo
     */
    public function commentTask()
    {
        return $this->belongsTo(Task::class, 'task');
    }
}
