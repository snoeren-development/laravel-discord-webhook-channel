<?php
declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordEmbed implements Arrayable
{
    /** The title of the embed. */
    protected string|null $title = null;

    /** The description of the embed. */
    protected string|null $description = null;

    /** The URL of the embed. */
    protected string|null $url = null;

    /** The ISO 8601 timestamp of the embed content. */
    protected string|null $timestamp = null;

    /** The color code of the embed. Hex encoded. Example: 0xFF0000 */
    protected int|null $color = null;

    /** The footer of the embed. */
    protected DiscordEmbedFooter|null $footer = null;

    /** The image of the embed. */
    protected DiscordEmbedUrlObject|null $image = null;

    /** The thumbnail of the embed. */
    protected DiscordEmbedUrlObject|null $thumbnail = null;

    /** The video of the embed. */
    protected DiscordEmbedUrlObject|null $video = null;

    /** The provider of the embed. */
    protected DiscordEmbedProvider|null $provider = null;

    /**  The author of the embed. */
    protected DiscordEmbedAuthor|null $author = null;

    /**
     * The fields of the embed.
     * 
     * @var \SnoerenDevelopment\DiscordWebhook\DiscordEmbedField[]
     */
    protected array $fields;

    /** Create a new Discord embed instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set the title of the embed. */
    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /** Set the description of the embed. */
    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /** Set the URL of the embed. */

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /** Set the ISO 8601 timestamp of the embed. */
    public function timestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /** Set the color code of the embed. Hex encoded. Example: 0xFF0000 */
    public function color(int $color): self
    {
        $this->color = $color;
        return $this;
    }

    /** Set the footer of the embed. */
    public function footer(DiscordEmbedFooter $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    /** Set the image of the embed. */
    public function image(DiscordEmbedUrlObject $image): self
    {
        $this->image = $image;
        return $this;
    }

    /** Set the thumbnail of the embed. */
    public function thumbnail(DiscordEmbedUrlObject $thumbnail): self
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /** Set the video of the embed. */
    public function video(DiscordEmbedUrlObject $video): self
    {
        $this->video = $video;
        return $this;
    }

    /** Set the provider of the embed. */
    public function provider(DiscordEmbedProvider $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    /** Set the author of the embed. */
    public function author(DiscordEmbedAuthor $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Add fields to the embed.
     *
     * @param  \SnoerenDevelopment\DiscordWebhook\DiscordEmbedField[] $fields Fields to add.
     */
    public function fields(array $fields): self
    {
        $this->fields = [...$this->fields, ...$fields];
        return $this;
    }

    /**  Add a field to the embed. */
    public function field(DiscordEmbedField $field): self
    {
        $this->fields = [...$this->fields, $field];
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => 'rich', // This is the only type supported in webhook messages.
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'timestamp' => $this->timestamp,
            'color' => $this->color,
            'footer' => $this->footer?->toArray(),
            'image' => $this->image?->toArray(),
            'thumbnail' => $this->thumbnail?->toArray(),
            'video' => $this->video?->toArray(),
            'provider' => $this->provider?->toArray(),
            'author' => $this->author?->toArray(),
            'fields' => array_map(
                fn(DiscordEmbedField $field) => $field->toArray(),
                $this->fields
            ),
        ], function ($value) {
            if (is_array($value) && count($value) === 0) {
                return false;
            }

            return !is_null($value);
        });
    }
}