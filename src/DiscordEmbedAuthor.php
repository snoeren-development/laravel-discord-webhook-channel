<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordEmbedAuthor implements Arrayable
{
    /**  The name of the embed author. */
    protected string|null $name = null;

    /** The url of the embed author. */
    protected string|null $url = null;

    /** The icon URL of the embed author. */
    protected string|null $iconUrl = null;

    /** The proxy icon URL of the embed author. */
    protected string|null $proxyIconUrl = null;

    /**  Create a new Discord embed author instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set the name of the embed author. */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** Set the URL of the embed author. */
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /** Set the icon URL of the embed author. */
    public function iconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }

    /** Set the proxy icon URL of the embed author. */
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
            'name' => $this->name,
            'url' => $this->url,
            'icon_url' => $this->iconUrl,
            'proxy_icon_url' => $this->proxyIconUrl,
        ], fn($value) => !is_null($value));
    }
}