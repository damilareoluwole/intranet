<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipient;
    public $message;
    public $attachments;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $recipient, string $message, array $attachments = [])
    {
        $this->recipient = $recipient;
        $this->message = $message;
        $this->attachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $displayName = $this->recipient;
        $info = $this->message;

        return $this->view('mails.info', compact('displayName', 'info'))
                    ->subject("Info from ".config('app.name'));
    }
}
