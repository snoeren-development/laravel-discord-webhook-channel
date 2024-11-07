<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordEmbedProvider implements Arrayable
{
    /** The name of the embed provider. */
    protected string|null $name;

    /** The URL of the embed provider. */
    protected string|null $url;

    /** Create a new Discord embed provider instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set the name of the embed provider. */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** Set the URL of the embed provider. */
    public function url(string $url): self
    {
        $this->url = $url;
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
        ], fn ($value) => !is_null($value));
    }
}