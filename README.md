# Laravel Discord Webhook Channel
[![Latest version on Packagist](https://img.shields.io/packagist/v/snoeren-development/laravel-discord-webhook-channel.svg?style=flat-square)](https://packagist.org/packages/snoeren-development/laravel-discord-webhook-channel)
[![Software License](https://img.shields.io/github/license/snoeren-development/laravel-discord-webhook-channel?style=flat-square)](LICENSE)
[![Build status](https://img.shields.io/github/workflow/status/snoeren-development/laravel-discord-webhook-channel/PHP%20Tests?style=flat-square)](https://github.com/snoeren-development/laravel-discord-webhook-channel/actions)
[![Downloads](https://img.shields.io/packagist/dt/snoeren-development/laravel-discord-webhook-channel?style=flat-square)](https://packagist.org/packages/snoeren-development/laravel-discord-webhook-channel)

## Installation
You can install the package using Composer:
```bash
composer require snoeren-development/laravel-discord-webhook-channel
```

### Requirements
This package requires at least PHP 7.2 and Laravel 6.

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
     *
     * @return string
     */
    public function routeNotificationForDiscord(): string
    {
        return $this->discord_webhook;
    }
}
```
The webhook URL can be created and retrieved via the Discord channel server Webhooks settings. The notification needs the full URL which looks like
```
https://discordapp.com/api/webhooks/1234567890123456789/1Px6cK9-9346g0CbOYArYjr1jj6X9rvRcCpRi3s7HePN0POeCSvuF1Iagb-Wjiq78BnT
```

You may now send notifications through Laravel to Discord webhooks using the `via` method.
```php
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class DiscordNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable The notifiable model.
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordWebhookChannel::class];
    }

    /**
     * Get the Discord representation of the notification.
     *
     * @param  mixed $notifiable The notifiable model.
     * @return array
     */
    public function toDiscord($notifiable): array
    {
        // The Discord webhook payload in array form.
        // See https://discordapp.com/developers/docs/resources/webhook#execute-webhook for all options.
        return [
            'username' => 'My Laravel App',
            'content' => 'The message body.',
        ];
    }
}
```

## Testing
```bash
$ composer test
```

## Credits
- [Michael Snoeren](https://github.com/MSnoeren)
- [All Contributors](https://github.com/snoeren-development/laravel-discord-webhook-channel/graphs/contributors)

## License
The MIT license. See [LICENSE](LICENSE) for more information.
