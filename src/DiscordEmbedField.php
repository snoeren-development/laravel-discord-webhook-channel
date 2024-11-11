<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordEmbedField implements Arrayable
{
    /** The name of the embed field. */
    protected string|null $name = null;

    /** The value of the embed field. */
    protected string|null $value = null;

    /** Indicates whether the field should be displayed inline. */
    protected bool|null $inline = null;

    /** Create a new Discord embed field instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set the name of the embed field. */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** Set the value of the embed field. */
    public function value(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /** Set whether the field should be displayed inline. */
    public function inline(bool $inline): self
    {
        $this->inline = $inline;
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
            'value' => $this->value,
            'inline' => $this->inline,
        ], fn ($value) => !is_null($value));
    }
}