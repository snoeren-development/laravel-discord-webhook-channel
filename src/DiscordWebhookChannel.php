<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;

class DiscordWebhookChannel
{
    /**
     * @var    \GuzzleHttp\Client $http The HTTP client.
     * @access protected
     */
    protected $http;

    /**
     * Constructor
     * @param  \GuzzleHttp\Client $http The HTTP client.
     * @access public
     * @return void
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Send the notification.
     * @param  mixed                                  $notifiable   The notifiable object.
     * @param  \Illuminate\Notifications\Notification $notification The notification object.
     * @access public
     * @return boolean
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$webhookUrl = $notifiable->routeNotificationFor('discord')) {
            return false;
        }

        // Get the payload of the notification.
        $payload = $notification->toDiscord($notifiable);

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
