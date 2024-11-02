<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;

class DiscordWebhookChannel
{
    /**
     * The HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected Client $http;

    /**
     * Constructor
     *
     * @param  \GuzzleHttp\Client $http The HTTP client.
     * @return void
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Send the notification.
     *
     * @param  mixed                                  $notifiable   The notifiable object.
     * @param  \Illuminate\Notifications\Notification $notification The notification object.
     * @return boolean
     */
    public function send($notifiable, Notification $notification): bool
    {
        if (!$webhookUrl = $notifiable->routeNotificationFor('discord')) {
            return false;
        }

        // Get the payload of the notification.
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
