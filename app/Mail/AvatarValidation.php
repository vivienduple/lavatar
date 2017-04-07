<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AvatarValidation extends Mailable
{
    use Queueable, SerializesModels;

    public $id, $tocken;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($avatarid, $tocken)
    {
        $this->id = $avatarid;
        $this->tocken = $tocken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.avatar.mail.validation');
    }
}
