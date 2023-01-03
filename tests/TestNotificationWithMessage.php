<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Illuminate\Notifications\Notification;
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;

class TestNotificationWithMessage extends Notification
{
    /**
     * Create the Discord notification.
     *
     * @param  mixed $notifiable The notifiable object.
     * @return \SnoerenDevelopment\DiscordWebhook\DiscordMessage
     */
    public function toDiscord($notifiable): DiscordMessage
    {
        return DiscordMessage::create()
            ->username('Unit Test')
            ->content('This is the message.');
    }
}
