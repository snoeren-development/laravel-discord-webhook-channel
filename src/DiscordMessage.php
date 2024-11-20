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
     * The embeds of the message.
     * 
     * @var \SnoerenDevelopment\DiscordWebhook\DiscordEmbed[]
     */
    protected array $embeds = [];

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
     * Add the message embeds.
     *
     * @param  \SnoerenDevelopment\DiscordWebhook\DiscordEmbed[] $embeds Embeds to add.
     */
    public function embeds(array $embeds): self
    {
        $this->embeds = [...$this->embeds, ...$embeds];
        return $this;
    }

    /**
     * Add the message embed.
     *
     * @param  \SnoerenDevelopment\DiscordWebhook\DiscordEmbed $embed The embed to add.
     */
    public function embed(DiscordEmbed $embed): self
    {
        $this->embeds = [...$this->embeds, $embed];
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
            'embeds' => array_map(
                fn(DiscordEmbed $embed) => $embed->toArray(),
                $this->embeds
            ),
        ], function ($value) {
            if (is_array($value) && count($value) === 0) {
                return false;
            }

            return !is_null($value);
        });
    }
}
