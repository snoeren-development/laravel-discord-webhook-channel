<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook;

final abstract class DiscordMessageFlags
{
    // Only these bitflags can be set when sending a message through a webhook.
    // @see https://discord.com/developers/docs/resources/webhook#execute-webhook-jsonform-params

    /**
     * Indicates that the message should not include any embeds when serializing this message.
     * 
     * @const int
     */
    public const SUPPRESS_EMBEDS = 1 << 2;

    /**
     * Indicates that the message should not trigger push and desktop notifications.
     * 
     * @const int
     */
    public const SUPPRESS_NOTIFICATIONS = 1 << 12;
}