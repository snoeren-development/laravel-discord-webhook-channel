<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

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
    public function testToArrayReturnsAllValuesWhenFilled()
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
}
