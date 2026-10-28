<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Illuminate\Notifications\Notifiable;

class TestNotifiableWithRoute
{
    use Notifiable;

    public function __construct(protected string $route)
    {
        //
    }

    /** Route the Discord notification. */
    public function routeNotificationForDiscord(): string
    {
        return $this->route;
    }
}
