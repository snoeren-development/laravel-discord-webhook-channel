<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    /**
     * Create the Discord notification.
     *
     * @param  mixed $notifiable The notifiable object.
     * @return array
     */
    public function toDiscord($notifiable)
    {
        return [
            'username' => 'Unit Test',
            'content' => 'This is the message.',
        ];
    }
}
