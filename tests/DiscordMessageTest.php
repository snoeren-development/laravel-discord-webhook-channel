<?php

declare(strict_types=1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use DateTimeImmutable;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbed;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedAuthor;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedField;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedFooter;
use SnoerenDevelopment\DiscordWebhook\DiscordEmbedUrlObject;
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;

class DiscordMessageTest extends TestCase
{
    /**
     * Test that the create method simply returns a new instance.
     *
     * @return void
     */
    public function testCreateMethodReturnsNewInstance(): void
    {
        $message = DiscordMessage::create();
        $this->assertInstanceOf(DiscordMessage::class, $message);
    }

    /**
     * Test that the content method throws an exception on empty input.
     *
     * @return void
     */
    public function testContentThrowsExceptionOnEmptyInput(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Content must not be empty.');

        DiscordMessage::create()->content('');
    }

    /**
     * Test that the toArray method only returns filled variables.
     *
     * @return void
     */
    public function testToArrayReturnsOnlyFilledEntries(): void
    {
        $result = DiscordMessage::create()
            ->content('Content')
            ->username('UnitTest')
            ->toArray();

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('username', $result);
        $this->assertArrayNotHasKey('avatar_url', $result);
        $this->assertArrayNotHasKey('tts', $result);
    }

    /**
     * Test that the toArray method returns all variables when filled.
     *
     * @return void
     */
    public function testToArrayReturnsAllValuesWhenFilled(): void
    {
        $result = DiscordMessage::create()
            ->content('Content')
            ->username('UnitTest')
            ->avatar('https://domain.com/some-avatar.jpg')
            ->tts(false)
            ->toArray();

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('username', $result);
        $this->assertArrayHasKey('avatar_url', $result);
        $this->assertArrayHasKey('tts', $result);
    }

    /**
     * Test that embeds are serialized into the Discord payload format.
     *
     * @return void
     */
    public function testEmbedsAreSerialized(): void
    {
        $result = DiscordMessage::create()
            ->embed(
                DiscordEmbed::create()
                    ->title('Title')
                    ->timestamp(new DateTimeImmutable('2026-01-02T03:04:05+00:00'))
                    ->color(0xFF0000)
                    ->author(DiscordEmbedAuthor::create()->name('Author')->iconUrl('https://domain.com/icon.png'))
                    ->footer(DiscordEmbedFooter::create()->text('Footer'))
                    ->image(DiscordEmbedUrlObject::create()->url('https://domain.com/image.png')->width(100))
                    ->field(DiscordEmbedField::create()->name('Name')->value('Value')->inline(true))
            )
            ->toArray();

        $this->assertSame([
            'embeds' => [
                [
                    'type' => 'rich',
                    'title' => 'Title',
                    'timestamp' => '2026-01-02T03:04:05+00:00',
                    'color' => 0xFF0000,
                    'footer' => ['text' => 'Footer'],
                    'image' => ['url' => 'https://domain.com/image.png', 'width' => 100],
                    'author' => ['name' => 'Author', 'icon_url' => 'https://domain.com/icon.png'],
                    'fields' => [
                        ['name' => 'Name', 'value' => 'Value', 'inline' => true],
                    ],
                ],
            ],
        ], $result);
    }
}
