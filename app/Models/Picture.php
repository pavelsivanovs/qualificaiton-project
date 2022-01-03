<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @Picture
 */
class Picture extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'path'
    ];

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'profile_picture');
    }

    /**
     * @return HasOne
     */
    public function project()
    {
        return $this->hasOne(Project::class, 'icon');
    }

    /**
     * @return HasOne
     */
    private function taskPicture()
    {
        return $this->hasOne(TaskPicture::class, 'picture');
    }

    /**
     * @return BelongsTo|null
     */
    public function task()
    {
        /** @var TaskPicture|null $taskPicture */
        $taskPicture = $this->taskPicture()->first();

        return $taskPicture ? $taskPicture->task() : null;
    }
}
