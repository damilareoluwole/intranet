<?php

namespace App\Notifications;

use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InfoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $attachment;
    protected $subject;
    protected $cc;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $subject = "Info", $cc = [], $attachment = [])
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->cc = $cc;
        $this->attachment = $attachment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $notifiable;
        $info = $this->message;
        $subject = $this->subject ?? 'Info';
        $displayName = $user->displayName ?? 'User';

        $mail = (new MailMessage)
            ->subject(config('app.name') . " - " . $subject)
            ->view('mails.info', compact('user', 'info', 'displayName'));

        if ($this->cc) {
            $mail = $mail->cc($this->cc);
        }

        if ($this->attachment) {
            $mail = $mail->attach($this->attachment['saved_path'], [
                'as' => $this->attachment['name'],
            ]);
        }

        $displayName = null;

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
