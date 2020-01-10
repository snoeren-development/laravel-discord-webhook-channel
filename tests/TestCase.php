<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\DiscordWebhook\Tests;

use Mockery;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Called after each test.
     * @access protected
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
