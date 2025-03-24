<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mukamail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fname = env('MAIL_FROM_NAME');

        return $this->view($this->data['view'])
            ->from($this->data['email'], $fname)
            ->subject($this->data['subject'])
            ->with('data', $this->data);
    }
}
