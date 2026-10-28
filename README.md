# Laravel Discord Webhook Channel

[![Latest version on Packagist](https://img.shields.io/packagist/v/snoeren-development/laravel-discord-webhook-channel.svg?style=flat-square)](https://packagist.org/packages/snoeren-development/laravel-discord-webhook-channel)
[![Software License](https://img.shields.io/github/license/snoeren-development/laravel-discord-webhook-channel?style=flat-square)](LICENSE)
[![Build status](https://img.shields.io/github/actions/workflow/status/snoeren-development/laravel-discord-webhook-channel/php.yml?branch=main&style=flat-square)](https://github.com/snoeren-development/laravel-discord-webhook-channel/actions)
[![Downloads](https://img.shields.io/packagist/dt/snoeren-development/laravel-discord-webhook-channel?style=flat-square)](https://packagist.org/packages/snoeren-development/laravel-discord-webhook-channel)

## Installation

You can install the package using Composer:

```bash
composer require snoeren-development/laravel-discord-webhook-channel
```

### Requirements

This package requires at least PHP 8.3 and Laravel 12.

## Usage

In every notifiable model you wish to notify via Discord, you need to add the `routeNotificationForDiscord` method;

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    /**
     * Route the notification for Discord.
     */
    public function routeNotificationForDiscord(): string
    {
        return $this->discord_webhook;
    }
}
```

The webhook URL can be created and retrieved via the Discord channel server Webhooks settings. The notification needs the full URL which looks like

```
https://discord.com/api/webhooks/1234567890123456789/1Px6cK9-9346g0CbOYArYjr1jj6X9rvRcCpRi3s7HePN0POeCSvuF1Iagb-Wjiq78BnT
```

For security reasons, the channel only sends requests to `https://` Discord webhook URLs (`discord.com`, `discordapp.com` and their
`ptb`/`canary` subdomains). Any other route throws an `InvalidArgumentException`, so a user supplied webhook URL can't be used to make your
application send requests to arbitrary hosts. Query parameters such as `?thread_id=` and `?wait=true` are supported.

You may now send notifications through Laravel to Discord webhooks using the `via` method.

```php
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class DiscordNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [DiscordWebhookChannel::class];
    }

    /**
     * Get the Discord representation of the notification.
     */
    public function toDiscord(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create()
            ->username('My Laravel App')
            ->content('The message body.')
            ->avatar('https://domain.com/avatar.jpg')
            ->tts(false);
    }
}
```

### Embeds

Messages may contain one or more rich embeds:

```php
use SnoerenDevelopment\DiscordWebhook\DiscordEmbed;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedAuthor;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedField;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedFooter;

DiscordMessage::create()
    ->content('A new order has been placed.')
    ->embed(
        DiscordEmbed::create()
            ->title('Order #1234')
            ->url('https://domain.com/orders/1234')
            ->color(0x5865F2)
            ->timestamp(now())
            ->author(DiscordEmbedAuthor::create()->name('Shop'))
            ->field(DiscordEmbedField::create()->name('Total')->value('€ 42,00')->inline(true))
            ->footer(DiscordEmbedFooter::create()->text('My Laravel App'))
    );
```

## Testing

```bash
composer test
```

## Credits

- [Michael Snoeren](https://github.com/MSnoeren)
- [All Contributors](https://github.com/snoeren-development/laravel-discord-webhook-channel/graphs/contributors)

## License

The MIT license. See [LICENSE](LICENSE) for more information.
