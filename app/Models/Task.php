<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @Task
 */
class Task extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'project',
        'status',
        'assignee',
        'deadline'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => TaskStatus::STATUS_PENDING
    ];

    /**
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project');
    }

    /**
     * @return BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status');
    }

    /**
     * @return BelongsTo
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee');
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task');
    }

    /**
     * @return HasMany
     */
    private function taskPictures()
    {
        return $this->hasMany(TaskPicture::class, 'task');
    }

    /**
     * @return Picture[]
     */
    public function pictures()
    {
        $taskPictures = $this->taskPictures()->get();
        $pictures = [];

        /** @var TaskPicture $taskPicture */
        foreach ($taskPictures as $taskPicture) {
           $pictures[] = $taskPicture->picture()->get();
        }
        return $pictures;
    }
}
