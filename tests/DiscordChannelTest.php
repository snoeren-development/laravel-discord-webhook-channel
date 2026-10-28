<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class DiscordChannelTest extends TestCase
{
    /**
     * Test that the channel can send a notification with array payload.
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
     * Test that the channel can send a notification with DiscordMessage object payload.
     *
     * @return void
     */
    public function testChannelCanSendNotificationAsObject(): void
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
        $this->assertTrue($channel->send(new TestNotifiable, new TestNotificationWithMessage));
    }

    /**
     * Test if the channel does not send anything if the notifiable does not have a route.
     *
     * @return void
     */
    public function testChannelDoesNotSendWhenNotifiableDoesntHaveRoute(): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::spy(HttpClient::class);
        $channel = new DiscordWebhookChannel($http);

        $this->assertFalse($channel->send(new TestNotifiableWithoutRoute, new TestNotification));
        $http->shouldNotHaveReceived('post');
    }

    /**
     * Test that the channel accepts any successful status code, e.g. "200 OK" when using "?wait=true".
     *
     * @return void
     */
    public function testChannelTreatsSuccessfulStatusAsSent(): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::mock(HttpClient::class);
        $http->shouldReceive('post')->once()->andReturn(new Response(200));

        $channel = new DiscordWebhookChannel($http);
        $notifiable = new TestNotifiableWithRoute('https://discord.com/api/webhooks/12345/super-secret?wait=true');

        $this->assertTrue($channel->send($notifiable, new TestNotification));
    }

    /**
     * Test that valid Discord webhook URLs are accepted.
     *
     * @return void
     */
    #[DataProvider('validWebhookUrls')]
    public function testChannelAcceptsDiscordWebhookUrls(string $url): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::mock(HttpClient::class);
        $http->shouldReceive('post')->once()->with($url, Mockery::any())->andReturn(new Response(204));

        $channel = new DiscordWebhookChannel($http);

        $this->assertTrue($channel->send(new TestNotifiableWithRoute($url), new TestNotification));
    }

    /**
     * Test that the channel refuses to send to anything but a Discord webhook URL.
     *
     * @return void
     */
    #[DataProvider('invalidWebhookUrls')]
    public function testChannelRejectsNonDiscordWebhookUrls(string $url): void
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $http */
        $http = Mockery::mock(HttpClient::class);
        $http->shouldNotReceive('post');

        $this->expectException(InvalidArgumentException::class);

        (new DiscordWebhookChannel($http))->send(new TestNotifiableWithRoute($url), new TestNotification);
    }

    /** @return array<string, array{string}> */
    public static function validWebhookUrls(): array
    {
        return [
            'discord.com' => ['https://discord.com/api/webhooks/123/abc-DEF_456'],
            'discordapp.com' => ['https://discordapp.com/api/webhooks/123/abc'],
            'canary with version' => ['https://canary.discord.com/api/v10/webhooks/123/abc'],
            'query string' => ['https://discord.com/api/webhooks/123/abc?thread_id=456'],
        ];
    }

    /** @return array<string, array{string}> */
    public static function invalidWebhookUrls(): array
    {
        return [
            'plain http' => ['http://discord.com/api/webhooks/123/abc'],
            'other host' => ['https://example.com/api/webhooks/123/abc'],
            'lookalike host' => ['https://discord.com.example.com/api/webhooks/123/abc'],
            'internal host' => ['https://169.254.169.254/api/webhooks/123/abc'],
            'credentials' => ['https://user:pass@discord.com/api/webhooks/123/abc'],
            'custom port' => ['https://discord.com:8443/api/webhooks/123/abc'],
            'non-webhook path' => ['https://discord.com/api/users/@me'],
            'path traversal' => ['https://discord.com/api/webhooks/123/../../users/@me'],
            'not a url' => ['not a url'],
        ];
    }
}
