<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * Route the Discord notification.
     *
     * @return string
     */
    public function routeNotificationForDiscord(): string
    {
        return 'https://discordapp.com/api/webhooks/12345/super-secret';
    }
}
