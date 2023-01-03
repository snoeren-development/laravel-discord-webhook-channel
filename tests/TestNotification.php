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
     * @return array<string, string>
     */
    public function toDiscord($notifiable): array
    {
        return [
            'username' => 'Unit Test',
            'content' => 'This is the message.',
        ];
    }
}
