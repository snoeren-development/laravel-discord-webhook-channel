<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;

class DiscordWebhookChannel
{
    public function __construct(protected Client $http)
    {
        //
    }

    public function send(mixed $notifiable, Notification $notification): bool
    {
        // @phpstan-ignore-next-line
        if (!$webhookUrl = $notifiable->routeNotificationFor('discord')) {
            return false;
        }

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

        return $response->getStatusCode() === Response::HTTP_NO_CONTENT;
    }
}
