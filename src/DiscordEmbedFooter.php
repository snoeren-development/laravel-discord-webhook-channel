<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordEmbedFooter implements Arrayable
{
    /** The text of the embed footer. */
    protected string|null $text = null;

    /** The icon URL of the embed footer. */
    protected string|null $iconUrl = null;

    /** The proxy icon URL of the embed footer. */
    protected string|null $proxyIconUrl = null;

    /** Create a new Discord embed footer instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set the text of the embed footer. */
    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /** Set the icon URL of the embed footer. */
    public function iconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }

    /** Set the proxy icon URL of the embed footer. */
    public function proxyIconUrl(string $proxyIconUrl): self
    {
        $this->proxyIconUrl = $proxyIconUrl;
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'icon_url' => $this->iconUrl,
            'proxy_icon_url' => $this->proxyIconUrl,
        ], function ($value) {
            return !is_null($value);
        });
    }
}