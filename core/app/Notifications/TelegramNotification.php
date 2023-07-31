<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;


class TelegramNotification extends Notification
{
    use Queueable;
    protected $messages;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($alert)
    {
        $this->message = $alert['message'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["telegram"];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegramId)
            ->content($this->message);
    }
}
