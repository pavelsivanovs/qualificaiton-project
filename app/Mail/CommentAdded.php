<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @CommentAdded
 */
class CommentAdded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Task
     */
    public $task;

    /**
     * @var User
     */
    public $comment_author;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task, User $comment_author)
    {
        $this->task = $task;
        $this->comment_author = $comment_author;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $task_url = config('app.url') . '/task/show/' . $this->task->id;

        return $this->markdown('emails.task.commentAdded')->with([
            'task_url' => $task_url
        ])->subject('Jauns komentārs');
    }
}
