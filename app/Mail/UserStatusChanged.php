<?php

namespace App\Mail;

use App\Models\UserStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @UserStatusChanged
 */
class UserStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var UserStatus
     */
    public $old_status;

    /**
     * @var UserStatus
     */
    public $new_status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($old_status, $new_status)
    {
        $this->old_status = $old_status;
        $this->new_status = $new_status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.statusChanged')->subject('Jauns lietotāja profila statuss');
    }
}
