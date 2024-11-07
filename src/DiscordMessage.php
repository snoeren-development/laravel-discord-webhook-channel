<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordMessage implements Arrayable
{
    /** The message content. */
    protected ?string $content = null;

    /** The username. */
    protected ?string $username = null;

    /** The avatar URL. */
    protected ?string $avatarUrl = null;

    /** Indicates that this is a Text-to-speech message. */
    protected ?bool $tts = null;

    /**
     * Controls what mentions are allowed in the message.
     * 
     * Allowed values: "everyone", "roles", "users"
     */
    protected array $allowedMentions = [];

    /**
     * Message flags.
     * 
     * @see \SnoerenDevelopment\DiscordWebhook\DiscordMessageFlags
     */
    protected int|null $flags = null;

    /** Create a new Discord message instance. */
    public static function create(): self
    {
        return new self;
    }

    /**
     * Set the content.
     *
     * @throws \InvalidArgumentException Thrown on empty content.
     */
    public function content(string $content): self
    {
        if (!strlen($content)) {
            throw new \InvalidArgumentException('Content must not be empty.');
        }

        $this->content = $content;
        return $this;
    }

    /** Set the username. */
    public function username(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /** Set the avatar url. */
    public function avatar(string $url): self
    {
        $this->avatarUrl = $url;
        return $this;
    }

    /** Set the TTS flag. */
    public function tts(bool $tts): self
    {
        $this->tts = $tts;
        return $this;
    }

    /**
     * Set the allowed mentions.
     *
     * @param  array<string> $allowedMentions The allowed mentions. Allowed values: "everyone", "roles", "users"
     */
    public function allowedMentions(array $allowedMentions): self
    {
        $this->allowedMentions = $allowedMentions;
        return $this;
    }

    /**
     * Set the message flags.
     * 
     * @see \SnoerenDevelopment\DiscordWebhook\DiscordMessageFlags
     * @param  int $flags The message flags.
     * @return \SnoerenDevelopment\DiscordWebhook\DiscordMessage
     */
    public function flags(int $flags): self
    {
        $this->flags = $flags;
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
            'content' => $this->content,
            'username' => $this->username,
            'avatar_url' => $this->avatarUrl,
            'tts' => $this->tts,
            'allowed_mentions' => $this->allowedMentions,
            'flags' => $this->flags,
        ], fn ($value) => !is_null($value));
    }
}
