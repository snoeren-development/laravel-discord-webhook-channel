<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class DiscordWebhookChannel
{
    /** The hosts Discord webhooks are served from. */
    protected const ALLOWED_HOSTS = [
        'discord.com',
        'ptb.discord.com',
        'canary.discord.com',
        'discordapp.com',
        'ptb.discordapp.com',
        'canary.discordapp.com',
    ];

    public function __construct(protected Client $http)
    {
        //
    }

    /**
     * Send the given notification.
     *
     * @throws \InvalidArgumentException Thrown when the route is not a valid Discord webhook URL.
     */
    public function send(mixed $notifiable, Notification $notification): bool
    {
        // @phpstan-ignore-next-line
        if (!$webhookUrl = $notifiable->routeNotificationFor('discord', $notification)) {
            return false;
        }

        $this->ensureValidWebhookUrl($webhookUrl);

        // Get the payload of the notification.
        // @phpstan-ignore-next-line
        $payload = $notification->toDiscord($notifiable);

        // Retrieve the message as array if a DiscordMessage has been returned.
        if ($payload instanceof DiscordMessage) {
            $payload = $payload->toArray();
        }

        // Send the request.
        $response = $this->http->post(
            $webhookUrl,
            [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        // Discord responds with "204 No Content" unless "?wait=true" is used.
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    /**
     * Ensure the URL points to a Discord webhook, so a user supplied
     * route cannot be abused to send requests to arbitrary hosts.
     *
     * @throws \InvalidArgumentException Thrown when the URL is not a valid Discord webhook URL.
     */
    protected function ensureValidWebhookUrl(mixed $url): void
    {
        $parts = is_string($url) ? parse_url($url) : false;

        if (
            $parts === false
            || strtolower($parts['scheme'] ?? '') !== 'https'
            || !in_array(strtolower($parts['host'] ?? ''), self::ALLOWED_HOSTS, true)
            || isset($parts['port']) || isset($parts['user']) || isset($parts['pass'])
            || !preg_match('#^/api(/v\d+)?/webhooks/\d+/[\w-]+/?$#', $parts['path'] ?? '')
        ) {
            throw new InvalidArgumentException('The Discord notification route must be a valid Discord webhook URL.');
        }
    }
}
