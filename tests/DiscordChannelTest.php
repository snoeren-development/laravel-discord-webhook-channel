<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Mockery;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as HttpClient;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class DiscordChannelTest extends TestCase
{
    /**
     * Test if the channel can send a notification.
     *
     * @return void
     */
    public function testChannelCanSendNotification(): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::mock(HttpClient::class);
        $http
            ->shouldReceive('post')
            ->once()
            ->with(
                'https://discordapp.com/api/webhooks/12345/super-secret',
                [
                    'json' => [
                        'username' => 'Unit Test',
                        'content' => 'This is the message.',
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                ]
            )
            ->andReturn(new Response(204));

        $channel = new DiscordWebhookChannel($http);
        $this->assertTrue($channel->send(new TestNotifiable, new TestNotification));
    }

    /**
     * Test if the channel does not send anything if the notifiable does not have a route.
     *
     * @return void
     */
    public function testChannelDoesNotSendWhenNotifiableDoesntHaveRoute(): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::spy(new HttpClient());
        $channel = new DiscordWebhookChannel($http);

        $this->assertFalse($channel->send(new TestNotifiableWithoutRoute, new TestNotification));
        $http->shouldNotHaveReceived('post');
    }
}
